<?php

namespace App\Actions\Subscription;

use App\DataTransferObjects\SubscriptionData;
use App\Models\Subscription;

class DeleteSubscriptionAction
{
    /**
     * @param SubscriptionData $subscriptionData
     * @return void
     */
    public function __invoke(SubscriptionData $subscriptionData): void
    {
        $subscription = Subscription::where('meta_data->subscription_id', $subscriptionData->meta_data['subscription_id'])->first();
        $subscription->cancellation_request_at = $subscriptionData->cancellation_request_at;
        $subscription->current_period_end_at = $subscriptionData->current_period_end_at;
        $subscription->status = $subscriptionData->status;
        $subscription->save();
    }
}
