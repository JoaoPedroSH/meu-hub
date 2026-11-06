<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class IntegrationController extends Controller
{
    public function index(Request $request): Response
    {
        $connection = $request->user()->googleCalendarConnection;

        return Inertia::render('Profile/Integrations', [
            'google' => [
                'configured' => $this->googleIsConfigured(),
                'connected' => $connection !== null,
                'email' => $connection?->google_email,
                'last_synced_at' => $connection?->updated_at?->format('d/m/Y H:i'),
            ],
        ]);
    }

    public function connect(Request $request): RedirectResponse
    {
        abort_unless($this->googleIsConfigured(), 503, 'Configure as credenciais locais do Google Agenda.');

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email https://www.googleapis.com/auth/calendar.events.readonly',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ]));
    }

    public function callback(Request $request): RedirectResponse
    {
        $expectedState = (string) $request->session()->pull('google_oauth_state', '');

        if ($request->filled('error') || $expectedState === '' || ! hash_equals($expectedState, (string) $request->input('state'))) {
            return redirect()->route('settings.integrations.index')->with('error', 'Não foi possível autorizar o Google Agenda.');
        }

        $tokenResponse = $this->googleHttp()->asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->string('code')->toString(),
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if (! $tokenResponse->successful() || ! $tokenResponse->json('access_token')) {
            return redirect()->route('settings.integrations.index')->with('error', 'O Google não autorizou a conexão. Tente novamente.');
        }

        $token = $tokenResponse->json();
        $userResponse = $this->googleHttp()->withToken($token['access_token'])->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if (! $userResponse->successful() || ! $userResponse->json('email')) {
            return redirect()->route('settings.integrations.index')->with('error', 'Não foi possível identificar a conta Google autorizada.');
        }

        $request->user()->googleCalendarConnection()->updateOrCreate([], [
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'] ?? $request->user()->googleCalendarConnection?->refresh_token,
            'token_expires_at' => now()->addSeconds((int) ($token['expires_in'] ?? 3600)),
            'google_email' => $userResponse->json('email'),
            'calendar_id' => 'primary',
        ]);

        return redirect()->route('settings.integrations.index')->with('success', 'Google Agenda conectado.');
    }

    public function sync(Request $request): RedirectResponse
    {
        $connection = $request->user()->googleCalendarConnection;

        if (! $connection) {
            return back()->with('error', 'Conecte o Google Agenda antes de sincronizar.');
        }

        if ($connection->token_expires_at?->isPast()) {
            if (! $connection->refresh_token) {
                return back()->with('error', 'A autorização expirou. Desconecte e conecte novamente.');
            }

            $refreshResponse = $this->googleHttp()->asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'refresh_token' => $connection->refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            if (! $refreshResponse->successful() || ! $refreshResponse->json('access_token')) {
                return back()->with('error', 'Não foi possível renovar a autorização do Google. Reconecte a conta.');
            }

            $connection->update([
                'access_token' => $refreshResponse->json('access_token'),
                'token_expires_at' => now()->addSeconds((int) $refreshResponse->json('expires_in', 3600)),
            ]);
        }

        $pageToken = null;
        $importedCount = 0;

        do {
            $response = $this->googleHttp()->withToken($connection->access_token)->get(
                'https://www.googleapis.com/calendar/v3/calendars/'.rawurlencode($connection->calendar_id).'/events',
                array_filter([
                    'singleEvents' => 'true',
                    'orderBy' => 'startTime',
                    'timeMin' => now()->subDays(30)->toRfc3339String(),
                    'timeMax' => now()->addYear()->toRfc3339String(),
                    'maxResults' => 2500,
                    'pageToken' => $pageToken,
                ]),
            );

            if (! $response->successful()) {
                return back()->with('error', 'O Google Agenda não concluiu a sincronização. Tente novamente.');
            }

            foreach ($response->json('items', []) as $event) {
                $start = $event['start']['dateTime'] ?? $event['start']['date'] ?? null;

                if (! $start || ($event['status'] ?? null) === 'cancelled' || empty($event['id'])) {
                    continue;
                }

                $request->user()->calendarEvents()->updateOrCreate(
                    ['source' => 'google', 'google_event_id' => $event['id']],
                    [
                        'note_id' => null,
                        'title' => $event['summary'] ?? '(Sem título)',
                        'description' => $event['description'] ?? null,
                        'starts_at' => $start,
                        'ends_at' => $event['end']['dateTime'] ?? $event['end']['date'] ?? null,
                        'all_day' => isset($event['start']['date']),
                        'color' => '#4285f4',
                        'location' => $event['location'] ?? null,
                    ],
                );
                $importedCount++;
            }

            $pageToken = $response->json('nextPageToken');
        } while ($pageToken);

        return back()->with('success', "Google Agenda sincronizado: {$importedCount} eventos atualizados.");
    }

    public function disconnect(Request $request): RedirectResponse
    {
        $request->user()->calendarEvents()->where('source', 'google')->delete();
        $request->user()->googleCalendarConnection()->delete();

        return back()->with('success', 'Google Agenda desconectado. Seus eventos locais foram mantidos.');
    }

    private function googleIsConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function googleHttp(): PendingRequest
    {
        return Http::connectTimeout(3)->timeout(10)->acceptJson();
    }
}
