<?php

namespace App\Actions\Subscription;

use App\DataTransferObjects\SubscriptionData;
use App\Models\Subscription;

class CreateSubscriptionAction
{
    /**
     * @param SubscriptionData $subscriptionData
     * @return void
     */
    public function __invoke(SubscriptionData $subscriptionData): void
    {
        Subscription::create($subscriptionData->toArray());
    }
}
