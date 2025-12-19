<?php

namespace App\Models;

use App\Casts\PriceConvertor;
use App\Enums\TransactionErrorType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'error_type',
        'transaction_id',
        'transactionable_id',
        'transactionable_type',
        'mid',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'amount' => PriceConvertor::class,
        'status' => TransactionStatus::class,
    ];

    /**
     * @return MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return hasOne
     */
    public function invoice(): hasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
