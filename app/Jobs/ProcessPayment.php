<?php

namespace App\Jobs;

use App\Contracts\PaymentGateway;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Helpers\Accounting;
use App\Models\Order;
use App\Models\Subscription;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Subscription $subscription,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PaymentGatewayRegistry $paymentGatewayRegistry): void
    {
        $paymentGateway = $paymentGatewayRegistry->get(config('hipay.default'));
        $paymentGateway->setSubscription(true);
        $paymentGateway->progressSubscriptionPayment($this->subscription);
    }
}
