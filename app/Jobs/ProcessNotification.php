<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Tracking;
use App\Notifications\Customers\NotificationEmailTrackingNumber;
use App\Notifications\Customers\NotificationSmsTrackingNumber;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $notification;

    public function __construct(
        public string $path,
        public string $fileName,
    ){
        $xml = Storage::get($path);

        $this->notification = json_decode(
            json: json_encode(
                simplexml_load_string(
                    data: $xml,
                    namespace_or_prefix: 'tnsb',
                    is_prefix: true
                )
            ),
            associative: true
        );
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $method = $this->eventToMethod($this->notification['Request']['Status']);

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * @return void
     */
    private function whenAccept(): void
    {
        if($this->notification['Request']['PaperOptions']['PostageClass'] === 'LRE_AR') {
            $id = explode('_', $this->notification['Request']['TrackId']);

            $customer = Customer::find($id[1]);

            if($customer) {
                Tracking::create([
                    'number' =>  $this->notification['Request']['ErlNumbers'],
                    'file_name' => $this->fileName,
                    'maileva_id' => $this->notification['Request']['TrackId'],
                    'customer_id' => $customer->id,
                ]);

                try {
                    Notification::send($customer, new NotificationEmailTrackingNumber($this->notification));
                } catch (\Exception $e) {
                    Log::error($e);
                }

                $c = new Carbon();
                $order = Order::query()
                    ->where('customer_id', $customer->id)
                    ->where('created_at', '<', $c->setTimestamp($id[2]))
                    ->orderByDesc('id')
                    ->first();

                if(collect($order->options)->filter(fn($option) => $option->name === 'sms_notification')->count() >= 1) {
                    Notification::send($customer, new NotificationSmsTrackingNumber($this->notification));
                }
            }
        }
    }

    /**
     * @return void
     */
    private function whenNaccept(): void
    {}

    /**
     * @return void
     */
    private function whenOk(): void
    {}

    /**
     * @return void
     */
    private function whenNok(): void
    {}

    /**
     * @param $event
     * @return string
     */
    private function eventToMethod($event): string
    {
        return 'when' . Str::of($event)->lower()->studly();
    }
}
