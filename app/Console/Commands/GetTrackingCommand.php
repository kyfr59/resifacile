<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Tracking;
use App\Notifications\Customers\NotificationEmailTrackingLaPosteNumber;
use App\Notifications\Customers\NotificationEmailTrackingNumber;
use App\Notifications\Customers\NotificationSmsTrackingLaPosteNumber;
use App\Notifications\Customers\NotificationSmsTrackingNumber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GetTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suivi la poste';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trackings = Tracking::where('is_active', true)->orderByDesc('id')->get();
        $trackings->each(function($tracking)  {
            $response = Http::withHeaders(['X-Okapi-Key' => 'g4XRxfXHkj5BJndV6wnFxeCwytxo2elTbyZmsPMeTrI+20QnCKATQwQvIHyZvIJN'])
                ->acceptJson()
                ->get('https://api.laposte.fr/suivi/v2/idships/' . $tracking->number, [
                    'lang' => 'fr_FR',
                ]);

            if($response->successful()) {
                $event = collect($response->json()['shipment']['timeline'])->filter(fn ($item) => $item['status'] === true)->last();

                $tracking->is_active = !$response->json()['shipment']['isFinal'];
                $tracking->save();

                try {
                    Notification::send($tracking->customer, new NotificationEmailTrackingLaPosteNumber(
                        $tracking->number,
                        $event
                    ));
                } catch (\Exception $e) {
                    Log::error($e);
                }

                $id = explode('_', $tracking->maileva_id);

                $c = new Carbon();
                $order = Order::query()->where('customer_id', $id[1])->where('created_at', '<', $c->setTimestamp($id[2]))->orderByDesc('id')->first();

                if(collect($order->options)->filter(fn($option) => $option->name === 'sms_notification')->count() >= 1) {
                    Notification::send(Customer::find($id[1]), new NotificationSmsTrackingLaPosteNumber(
                        $tracking->number,
                        $event
                    ));
                }
            }
        });
    }
}
