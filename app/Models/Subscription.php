<?php

namespace App\Models;

use App\Casts\PriceConvertor;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'price',
        'cancellation_request_at',
        'current_period_end_at',
        'attempts',
        'status',
        'meta_data',
        'customer_id',
        'discount_rate',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'price' => PriceConvertor::class,
        'cancellation_request_at' => 'date',
        'current_period_end_at' => 'date',
        'status' => SubscriptionStatus::class,
        'meta_data' => 'object',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
