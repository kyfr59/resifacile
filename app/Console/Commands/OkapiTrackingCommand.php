<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Tracking;
use App\Exceptions\OkapiException;
use App\Services\OkapiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Events\TrackingStatusChanged;

class OkapiTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'okapi-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suivi la poste';

    /**
     * Execute the console command.
     */
    public function handle(OkapiService $okapi)
    {
        $trackings = Tracking::where('is_final', false)->get();

        foreach ($trackings as $tracking) {
            try {
                $data = $okapi->track($tracking->id_ship);
            } catch (OkapiException $e) {
                $this->error("Erreur pour {$tracking->id_ship} : {$e->getMessage()}");
                continue;
            }

            $latestEvent = $data['shipment']['event'][0] ?? null;

            if (! $latestEvent) {
                continue;
            }

            $hasChanged = $tracking->last_event_code !== $latestEvent['code'];
             // || $tracking->last_event_date?->toIso8601String() !== $latestEvent['date'];

            if ($hasChanged) {
                $tracking->update([
                    'last_event_code' => $latestEvent['code'],
                    'last_event_date' => $latestEvent['date'],
                    'is_final' => (bool) ($data['shipment']['isFinal'] ?? false),
                ]);

                event(new TrackingStatusChanged($tracking, $latestEvent, $data['shipment']));

                $this->info("Changement détecté : {$tracking->id_ship} → {$latestEvent['label']}");
            }
        }

        return self::SUCCESS;
    }
}
