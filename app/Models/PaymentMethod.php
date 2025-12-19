<?php

namespace App\Models;

use App\Enums\PaymentMethodStatus;
use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'type',
        'data',
        'customer_id',
        'status',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'type' => PaymentMethodType::class,
        'data' => 'object',
        'status' => PaymentMethodStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
