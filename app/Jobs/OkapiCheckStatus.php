<?php

namespace App\Jobs;

use App\Exceptions\OkapiException;
use App\Models\Tracking;
use App\Services\OkapiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Okapi\StatusChangedToRE1;

class OkapiCheckStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Tracking $tracking) {}

    public function handle(OkapiService $okapi): void
    {
        try {
            $data = $okapi->track($this->tracking->id_ship);
        } catch (OkapiException $e) {
            Log::warning("Okapi check failed for tracking #{$this->tracking->id} ({$this->tracking->id_ship}): {$e->getMessage()}");
            return;
        }

        if (! $data['success'] || empty($data['events'])) {
            return;
        }

        $events = $data['events'];
        $eventCodesPresent = array_unique(array_filter(array_column($events, 'code')));
        $alreadyNotified = $this->tracking->notified_event_codes ?? [];
        $watchedCodes = config('okapi.watched_event_codes');

        $codesToNotify = array_values(array_diff(
            array_intersect($eventCodesPresent, $watchedCodes),
            $alreadyNotified
        ));

        // Mise à jour des infos générales (toujours, même sans code à notifier)
        $latestEvent = $events[0];
        $this->tracking->last_event_code = $latestEvent['code'] ?? $this->tracking->last_event_code;
        $this->tracking->last_event_date = $latestEvent['date'] ?? $this->tracking->last_event_date;
        $this->tracking->is_final = $data['isFinal'] ?? $this->tracking->is_final;

        // On ne garde que les codes dont l'envoi a réellement réussi
        $successfullyNotified = [];

        foreach ($codesToNotify as $code) {
            $matchedEvent = collect($events)->firstWhere('code', $code);

            if ($this->notify($code, $matchedEvent ?? [])) {
                $successfullyNotified[] = $code;
            }
        }

        if (! empty($successfullyNotified)) {
            $this->tracking->notified_event_codes = array_values(
                array_unique(array_merge($alreadyNotified, $successfullyNotified))
            );
        }

        $this->tracking->save();
    }

    protected array $codeToMailable = [
        'ET1'   => \App\Mail\Okapi\StatusChangedToET1::class,
        'RE1'   => \App\Mail\Okapi\StatusChangedToRE1::class,
        'DI1'   => \App\Mail\Okapi\StatusChangedToDI1::class,
    ];

    protected function notify(string $code, array $event): bool
    {
        $customer = $this->tracking->sending?->customer ?? null;

        if (! $customer) {
            throw new OkapiException(
                message  : "Tracking #{$this->tracking->id} : code {$code} détecté mais aucun destinataire trouvé.",
            );
        }

        // $code = 'DI1'; // Fake pour tester

        $mailableClass = $this->codeToMailable[$code] ?? null;

        if ($mailableClass === null) {
            throw new OkapiException(
                message  : "Impossible de trouver le modèle d'email de notification du statut Okapi {$code}",
            );
        }

        try {
            Mail::to($customer->email)->send(new $mailableClass($this->tracking, $code, $event));
            return true;
        } catch (\Exception $e) {
            throw new OkapiException(
                message  : "Impossible d'envoyer le mail de notification du statut Okapi {$code}",
            );
        }
    }
}
