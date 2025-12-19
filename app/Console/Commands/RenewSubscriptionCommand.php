<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Jobs\ProcessPayment;
use App\Models\Subscription;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Console\Command;

class RenewSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renouvellement des abonnements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = Subscription::query()
            ->whereNull('cancellation_request_at')
            ->whereIn('status', [
                SubscriptionStatus::TRIAL->value,
                SubscriptionStatus::RECURRING->value,
                SubscriptionStatus::LATE_PAYMENT->value,
            ])
            ->where('meta_data->mid', config('hipay.default'))
            ->whereDate('current_period_end_at', '<=', now())
            ->get();

        foreach ($subscriptions as $subscription) {
            if(
                ($subscription->current_period_end_at->diff(now())->days === 0)||
                ($subscription->current_period_end_at->diff(now())->days === 2)||
                ($subscription->current_period_end_at->diff(now())->days === 5)||
                ($subscription->current_period_end_at->diff(now())->days === 8) ||
                ($subscription->current_period_end_at->diff(now())->days === 11) ||
                ($subscription->current_period_end_at->diff(now())->days > 30 && now()->day === 5) ||
                ($subscription->current_period_end_at->diff(now())->days > 30 && now()->day === 10)
            ) {
                ProcessPayment::dispatch($subscription);
            }
        }

        $subscriptions = Subscription::query()
            ->whereNotNull('cancellation_request_at')
            ->whereIn('status', [
                SubscriptionStatus::CANCEL_REQUEST->value,
            ])
            ->where('meta_data->mid', config('hipay.default'))
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->status = SubscriptionStatus::CANCELED->value;
            $subscription->save();
        }
    }
}
