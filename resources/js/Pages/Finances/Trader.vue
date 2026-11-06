<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    accounts: { type: Array, default: () => [] },
    quotes: { type: Array, default: () => [] },
    symbols: { type: Array, default: () => [] },
    quoteUpdatedAt: { type: String, default: null },
});

const selectedAccountId = ref(props.accounts[0]?.id ?? '');
const selectedAccount = computed(() => props.accounts.find((account) => account.id === Number(selectedAccountId.value)) ?? null);
const quoteForSymbol = computed(() => props.quotes.find((quote) => quote.symbol === orderForm.symbol) ?? null);

const accountForm = useForm({ name: '', initial_balance: '10000' });
const orderForm = useForm({ symbol: props.symbols[0]?.symbol ?? 'BTCUSDT', side: 'buy', quantity: '' });

function createAccount() {
    accountForm.post(route('finance.trader.accounts.store'), {
        preserveScroll: true,
        onSuccess: () => accountForm.reset('name'),
    });
}

function submitOrder(side) {
    if (!selectedAccountId.value) {
        return;
    }

    orderForm.side = side;
    orderForm.post(route('finance.trader.orders.store', selectedAccountId.value), {
        preserveScroll: true,
        onSuccess: () => orderForm.reset('quantity'),
    });
}

function formatAmount(value, decimals = 2) {
    if (value === null || value === undefined || value === '') {
        return '—';
    }

    return Number(value).toLocaleString('pt-BR', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

function formatChange(value) {
    return `${Number(value) > 0 ? '+' : ''}${formatAmount(value, 2)}%`;
}
</script>

<template>
    <Head title="Trader · Simulação" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8">
            <header class="border-b border-slate-200 pb-6">
                <nav class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Finanças</span><span>/</span><span class="text-slate-600">Trader</span>
                </nav>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Trader</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Pratique operações no mercado cripto spot usando carteiras virtuais e cotações públicas.</p>
                    </div>
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span> Simulação · sem dinheiro real
                    </span>
                </div>
            </header>

            <section class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 text-sm leading-6 text-amber-950 sm:p-5">
                Nenhuma corretora está conectada. As ordens abaixo são fictícias, não saem desta instalação e não representam recomendação de investimento. As cotações públicas podem atrasar ou ficar indisponíveis.
            </section>

            <section>
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Mercado cripto spot</h2>
                        <p class="mt-1 text-sm text-slate-500">Fonte: Binance Spot · ativos cotados em USDT</p>
                    </div>
                    <p v-if="quoteUpdatedAt" class="text-xs text-slate-400">Cotação consultada em {{ quoteUpdatedAt }}</p>
                </div>

                <div v-if="quotes.length" class="grid gap-3 sm:grid-cols-3">
                    <article v-for="quote in quotes" :key="quote.symbol" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ quote.symbol }}</p>
                                <h3 class="mt-1 font-semibold text-slate-900">{{ quote.label }}</h3>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold" :class="Number(quote.change_percent) >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">{{ formatChange(quote.change_percent) }}</span>
                        </div>
                        <p class="mt-5 text-2xl font-bold tracking-tight text-slate-900">{{ formatAmount(quote.price, 4) }} <span class="text-sm font-medium text-slate-400">USDT</span></p>
                        <p class="mt-1 text-xs text-slate-400">Variação nas últimas 24 horas</p>
                    </article>
                </div>
                <div v-else class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm leading-6 text-slate-500">
                    Não foi possível consultar as cotações públicas. A simulação de ordens fica indisponível até os preços voltarem.
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Carteiras de simulação</h2>
                                <p class="mt-1 text-sm text-slate-500">Crie carteiras separadas para experimentar estratégias sem conectar contas reais.</p>
                            </div>
                            <span class="w-fit rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ accounts.length }} {{ accounts.length === 1 ? 'carteira' : 'carteiras' }}</span>
                        </div>

                        <form class="mt-5 grid gap-3 sm:grid-cols-[minmax(0,1fr)_180px_auto]" @submit.prevent="createAccount">
                            <div>
                                <label for="account-name" class="sr-only">Nome da carteira</label>
                                <input id="account-name" v-model="accountForm.name" required maxlength="80" placeholder="Nome da carteira (ex.: Estratégia conservadora)" class="w-full rounded-xl border-slate-300 text-sm" />
                                <p v-if="accountForm.errors.name" class="mt-1 text-xs text-rose-600">{{ accountForm.errors.name }}</p>
                            </div>
                            <div>
                                <label for="initial-balance" class="sr-only">Saldo inicial simulado em USDT</label>
                                <input id="initial-balance" v-model="accountForm.initial_balance" type="number" min="100" max="1000000" step="100" required class="w-full rounded-xl border-slate-300 text-sm" />
                                <p v-if="accountForm.errors.initial_balance" class="mt-1 text-xs text-rose-600">{{ accountForm.errors.initial_balance }}</p>
                            </div>
                            <button type="submit" :disabled="accountForm.processing" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-60">Criar carteira</button>
                        </form>

                        <p v-if="!accounts.length" class="mt-6 rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">Ainda não há carteiras. Crie uma acima para começar a simular.</p>

                        <div v-else class="mt-5 grid gap-3 md:grid-cols-2">
                            <button v-for="account in accounts" :key="account.id" type="button" class="rounded-xl border p-4 text-left transition" :class="selectedAccountId === account.id ? 'border-emerald-300 bg-emerald-50/50 ring-1 ring-emerald-200' : 'border-slate-200 hover:border-slate-300'" @click="selectedAccountId = account.id">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ account.name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">Conta local · simulação spot</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-800">Paper</span>
                                </div>
                                <p class="mt-4 text-xs font-medium uppercase tracking-wider text-slate-400">Saldo disponível</p>
                                <p class="mt-1 text-xl font-bold text-slate-900">{{ formatAmount(account.cash_balance) }} <span class="text-sm font-medium text-slate-400">USDT</span></p>
                            </button>
                        </div>
                    </div>

                    <div v-if="selectedAccount" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Posições simuladas</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ selectedAccount.name }} · P&L não realizado estimado</p>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Caixa: {{ formatAmount(selectedAccount.cash_balance) }} USDT</p>
                        </div>
                        <div v-if="selectedAccount.positions.length" class="mt-4 overflow-x-auto">
                            <table class="w-full min-w-[640px] text-left text-sm">
                                <thead class="border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400"><tr><th class="py-3 pr-4 font-semibold">Ativo</th><th class="py-3 pr-4 font-semibold">Quantidade</th><th class="py-3 pr-4 font-semibold">Preço médio</th><th class="py-3 pr-4 font-semibold">Cotação</th><th class="py-3 font-semibold">P&L estimado</th></tr></thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="position in selectedAccount.positions" :key="position.id">
                                        <td class="py-3 pr-4 font-semibold text-slate-800">{{ position.symbol }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ formatAmount(position.quantity, 8) }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ formatAmount(position.average_entry_price, 4) }} USDT</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ position.quote ? `${formatAmount(position.quote.price, 4)} USDT` : 'Indisponível' }}</td>
                                        <td class="py-3 font-semibold" :class="Number(position.unrealized_pnl) >= 0 ? 'text-emerald-700' : 'text-rose-700'">{{ position.unrealized_pnl === null ? '—' : `${formatAmount(position.unrealized_pnl)} USDT` }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else class="mt-5 rounded-xl bg-slate-50 p-5 text-sm text-slate-500">Esta carteira ainda não tem posições.</p>
                    </div>

                    <div v-if="selectedAccount" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="text-lg font-semibold text-slate-900">Operações recentes</h2>
                        <div v-if="selectedAccount.orders.length" class="mt-4 overflow-x-auto">
                            <table class="w-full min-w-[680px] text-left text-sm">
                                <thead class="border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400"><tr><th class="py-3 pr-4 font-semibold">Data</th><th class="py-3 pr-4 font-semibold">Lado</th><th class="py-3 pr-4 font-semibold">Ativo</th><th class="py-3 pr-4 font-semibold">Quantidade</th><th class="py-3 pr-4 font-semibold">Preço simulado</th><th class="py-3 font-semibold">Taxa simulada</th></tr></thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="order in selectedAccount.orders" :key="order.id">
                                        <td class="py-3 pr-4 text-slate-500">{{ order.executed_at }}</td>
                                        <td class="py-3 pr-4"><span class="rounded-full px-2 py-1 text-xs font-semibold" :class="order.side === 'buy' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">{{ order.side === 'buy' ? 'Compra' : 'Venda' }}</span></td>
                                        <td class="py-3 pr-4 font-medium text-slate-800">{{ order.symbol }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ formatAmount(order.quantity, 8) }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ formatAmount(order.execution_price, 4) }} USDT</td>
                                        <td class="py-3 text-slate-600">{{ formatAmount(order.fee_amount, 4) }} USDT</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else class="mt-4 text-sm text-slate-500">As operações simuladas aparecerão aqui.</p>
                    </div>
                </div>

                <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:sticky xl:top-6">
                    <h2 class="text-lg font-semibold text-slate-900">Nova ordem simulada</h2>
                    <p class="mt-1 text-sm text-slate-500">Somente mercado à vista, sem margem ou alavancagem.</p>

                    <form class="mt-5 space-y-4" @submit.prevent="submitOrder(orderForm.side)">
                        <div>
                            <label for="order-account" class="mb-1.5 block text-sm font-medium text-slate-700">Carteira</label>
                            <select id="order-account" v-model="selectedAccountId" required class="w-full rounded-xl border-slate-300 text-sm" :disabled="!accounts.length">
                                <option value="" disabled>Crie uma carteira primeiro</option>
                                <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="order-symbol" class="mb-1.5 block text-sm font-medium text-slate-700">Ativo</label>
                            <select id="order-symbol" v-model="orderForm.symbol" class="w-full rounded-xl border-slate-300 text-sm">
                                <option v-for="symbol in symbols" :key="symbol.symbol" :value="symbol.symbol">{{ symbol.symbol }} · {{ symbol.label }}</option>
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">Cotação: {{ quoteForSymbol ? `${formatAmount(quoteForSymbol.price, 4)} USDT` : 'indisponível' }}</p>
                        </div>
                        <div>
                            <label for="order-quantity" class="mb-1.5 block text-sm font-medium text-slate-700">Quantidade</label>
                            <input id="order-quantity" v-model="orderForm.quantity" required type="number" min="0.00000001" max="100" step="0.00000001" placeholder="0.001" class="w-full rounded-xl border-slate-300 text-sm" />
                            <p v-if="orderForm.errors.quantity" class="mt-1 text-xs text-rose-600">{{ orderForm.errors.quantity }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <button type="button" :disabled="!selectedAccount || !quoteForSymbol || orderForm.processing" class="rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50" @click="submitOrder('buy')">Simular compra</button>
                            <button type="button" :disabled="!selectedAccount || !quoteForSymbol || orderForm.processing" class="rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-50" @click="submitOrder('sell')">Simular venda</button>
                        </div>
                    </form>

                    <div class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-xs leading-5 text-slate-500">
                        <p>Taxa estimada: 0,1% por operação, apenas para simulação.</p>
                        <p>As operações usam a última cotação pública consultada e não simulam liquidez, spread ou slippage.</p>
                    </div>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
