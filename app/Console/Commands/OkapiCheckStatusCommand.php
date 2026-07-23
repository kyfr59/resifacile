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
use App\Jobs\OkapiCheckStatus;

class OkapiCheckStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'okapi-check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérification des changements de statuts Okapi';

    /**
     * Execute the console command.
     */
    public function handle(OkapiService $okapi)
    {
        Tracking::query()
            ->where('is_final', false)
            ->whereNotNull('id_ship')
            ->chunkById(50, function ($trackings) {
                foreach ($trackings as $tracking) {
                    OkapiCheckStatus::dispatch($tracking)->onQueue('tracking');
                }
        });
    }
}
