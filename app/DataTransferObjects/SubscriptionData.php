<?php

namespace App\DataTransferObjects;

use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class SubscriptionData extends Data
{
    public function __construct(
        public string $designation,
        public float $price,
        public SubscriptionStatus $status,
        public array $meta_data,
        public int $customer_id,
        public Carbon $current_period_end_at,
        public ?Carbon $cancellation_request_at = null,
        public int $discount_rate = 0,
    public int $attempts = 0,
    ){}
}
