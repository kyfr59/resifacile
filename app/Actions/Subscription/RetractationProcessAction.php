<?php

namespace App\Actions\Subscription;

use App\Enums\SubscriptionStatus;
use App\Mail\UnsubcribeConfirmation;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class RetractationProcessAction
{
    public function __construct(
        public Subscription $subscription
    ){}

    public function process()
    {
        $this->subscription->cancellation_request_at = now();
        $this->subscription->current_period_end_at = now();
        $this->subscription->status = SubscriptionStatus::RETRACTED;
        $this->subscription->save();

        Mail::to($this->subscription->customer->email)->send(new UnsubcribeConfirmation($this->subscription));
    }
}
