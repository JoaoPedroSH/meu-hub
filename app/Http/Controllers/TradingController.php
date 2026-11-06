<?php

namespace App\Http\Controllers;

use App\Models\TradingAccount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TradingController extends Controller
{
    private const SYMBOLS = [
        'BTCUSDT' => 'Bitcoin / Tether',
        'ETHUSDT' => 'Ethereum / Tether',
        'SOLUSDT' => 'Solana / Tether',
    ];

    private const SIMULATED_FEE_RATE = '0.001';

    public function index(Request $request): Response
    {
        $quotes = $this->marketQuotes();
        $quoteMap = collect($quotes)->keyBy('symbol');

        $accounts = $request->user()->tradingAccounts()
            ->with([
                'positions' => fn ($query) => $query->where('quantity', '>', 0)->orderBy('symbol'),
                'orders' => fn ($query) => $query->latest('executed_at')->limit(8),
            ])
            ->latest()
            ->get()
            ->map(function (TradingAccount $account) use ($quoteMap): array {
                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'cash_balance' => $account->cash_balance,
                    'base_currency' => $account->base_currency,
                    'positions' => $account->positions->map(function ($position) use ($quoteMap): array {
                        $quote = $quoteMap->get($position->symbol);

                        return [
                            'id' => $position->id,
                            'symbol' => $position->symbol,
                            'quantity' => $position->quantity,
                            'average_entry_price' => $position->average_entry_price,
                            'realized_pnl' => $position->realized_pnl,
                            'quote' => $quote,
                            'unrealized_pnl' => $quote
                                ? bcsub(
                                    bcmul((string) $quote['price'], (string) $position->quantity, 8),
                                    bcmul((string) $position->average_entry_price, (string) $position->quantity, 8),
                                    8,
                                )
                                : null,
                        ];
                    })->values(),
                    'orders' => $account->orders->map(fn ($order): array => [
                        'id' => $order->id,
                        'symbol' => $order->symbol,
                        'side' => $order->side,
                        'quantity' => $order->quantity,
                        'execution_price' => $order->execution_price,
                        'gross_amount' => $order->gross_amount,
                        'fee_amount' => $order->fee_amount,
                        'realized_pnl' => $order->realized_pnl,
                        'executed_at' => $order->executed_at->format('d/m/Y H:i'),
                    ])->values(),
                ];
            })->values();

        return Inertia::render('Finances/Trader', [
            'accounts' => $accounts,
            'quotes' => $quotes,
            'symbols' => collect(self::SYMBOLS)
                ->map(fn (string $label, string $symbol): array => ['symbol' => $symbol, 'label' => $label])
                ->values(),
            'quoteUpdatedAt' => $quotes[0]['updated_at'] ?? null,
        ]);
    }

    public function storeAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'initial_balance' => ['required', 'numeric', 'decimal:0,2', 'min:100', 'max:1000000'],
        ]);

        $request->user()->tradingAccounts()->create([
            'name' => $validated['name'],
            'market' => 'crypto_spot',
            'provider' => 'binance_public',
            'mode' => 'paper',
            'base_currency' => 'USDT',
            'initial_balance' => $validated['initial_balance'],
            'cash_balance' => $validated['initial_balance'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Carteira simulada criada. Nenhuma corretora foi conectada.');
    }

    public function storeOrder(Request $request, int $accountId): RedirectResponse
    {
        $validated = $request->validate([
            'symbol' => ['required', 'string', Rule::in(array_keys(self::SYMBOLS))],
            'side' => ['required', 'string', Rule::in(['buy', 'sell'])],
            'quantity' => ['required', 'numeric', 'decimal:0,8', 'gt:0', 'max:100'],
        ]);

        $account = $request->user()->tradingAccounts()->whereKey($accountId)->first();

        if (! $account) {
            throw (new ModelNotFoundException)->setModel(TradingAccount::class, [$accountId]);
        }

        Cache::forget('meuhub.trader.crypto_spot_quotes');
        $quote = collect($this->marketQuotes())->firstWhere('symbol', $validated['symbol']);

        if (! $quote) {
            return back()->with('error', 'Não foi possível obter uma cotação pública atualizada. Nenhuma operação foi registrada.');
        }

        $quantity = bcadd((string) $validated['quantity'], '0', 8);
        $price = (string) $quote['price'];
        $grossAmount = bcmul($quantity, $price, 8);
        $feeAmount = bcmul($grossAmount, self::SIMULATED_FEE_RATE, 8);

        DB::transaction(function () use ($request, $accountId, $validated, $quantity, $price, $grossAmount, $feeAmount): void {
            $account = $request->user()->tradingAccounts()->whereKey($accountId)->lockForUpdate()->firstOrFail();
            $position = $account->positions()->where('symbol', $validated['symbol'])->lockForUpdate()->first();
            $realizedPnl = '0.00000000';

            if ($validated['side'] === 'buy') {
                $totalCost = bcadd($grossAmount, $feeAmount, 8);

                if (bccomp((string) $account->cash_balance, $totalCost, 8) < 0) {
                    throw ValidationException::withMessages(['quantity' => 'Saldo simulado insuficiente para essa compra e a taxa estimada.']);
                }

                $previousQuantity = $position?->quantity ?? '0.00000000';
                $newQuantity = bcadd((string) $previousQuantity, $quantity, 8);
                $previousCost = bcmul((string) $previousQuantity, (string) ($position?->average_entry_price ?? '0'), 8);
                $newCost = bcadd(bcmul($quantity, $price, 8), $feeAmount, 8);
                $averageEntryPrice = bcdiv(bcadd($previousCost, $newCost, 8), $newQuantity, 8);

                $account->update(['cash_balance' => bcsub((string) $account->cash_balance, $totalCost, 8)]);
                $account->positions()->updateOrCreate(
                    ['symbol' => $validated['symbol']],
                    [
                        'quantity' => $newQuantity,
                        'average_entry_price' => $averageEntryPrice,
                        'realized_pnl' => $position?->realized_pnl ?? '0.00000000',
                    ],
                );
            } else {
                if (! $position || bccomp((string) $position->quantity, $quantity, 8) < 0) {
                    throw ValidationException::withMessages(['quantity' => 'A quantidade vendida não pode exceder sua posição simulada.']);
                }

                $netProceeds = bcsub($grossAmount, $feeAmount, 8);
                $realizedPnl = bcsub(
                    bcmul(bcsub($price, (string) $position->average_entry_price, 8), $quantity, 8),
                    $feeAmount,
                    8,
                );
                $remainingQuantity = bcsub((string) $position->quantity, $quantity, 8);

                $account->update(['cash_balance' => bcadd((string) $account->cash_balance, $netProceeds, 8)]);
                $position->update([
                    'quantity' => $remainingQuantity,
                    'realized_pnl' => bcadd((string) $position->realized_pnl, $realizedPnl, 8),
                ]);
            }

            $account->orders()->create([
                'symbol' => $validated['symbol'],
                'side' => $validated['side'],
                'quantity' => $quantity,
                'execution_price' => $price,
                'gross_amount' => $grossAmount,
                'fee_amount' => $feeAmount,
                'realized_pnl' => $realizedPnl,
                'status' => 'filled',
                'executed_at' => now(),
            ]);
        });

        return back()->with('success', 'Operação simulada registrada com cotação pública.');
    }

    private function marketQuotes(): array
    {
        return Cache::remember('meuhub.trader.crypto_spot_quotes', now()->addSeconds(15), function (): array {
            try {
                $response = Http::connectTimeout(2)
                    ->timeout(4)
                    ->get('https://api.binance.com/api/v3/ticker/24hr', [
                        'symbols' => json_encode(array_keys(self::SYMBOLS), JSON_THROW_ON_ERROR),
                    ]);

                if (! $response->successful()) {
                    return [];
                }

                return collect($response->json())
                    ->filter(fn (array $ticker): bool => isset($ticker['symbol'], $ticker['lastPrice']) && array_key_exists($ticker['symbol'], self::SYMBOLS))
                    ->map(fn (array $ticker): array => [
                        'symbol' => $ticker['symbol'],
                        'label' => self::SYMBOLS[$ticker['symbol']],
                        'price' => $ticker['lastPrice'],
                        'change_percent' => $ticker['priceChangePercent'] ?? null,
                        'updated_at' => now()->format('d/m/Y H:i:s'),
                    ])
                    ->values()
                    ->all();
            } catch (ConnectionException) {
                return [];
            }
        });
    }
}
