<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\DataTransferObjects\PostLetter\SendingData;
use App\Enums\SendingStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sending extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'provider_id',
        'status',
        'sent_at',
        'delivered_at',
        'campaign_id',
        'maileva',
    ];

    protected $casts = [
        'data' => SendingData::class,
        'status' => SendingStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'maileva' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function fromMailevaId($mailevaId)
    {
        return static::where('maileva->sending_id', $mailevaId)->first();
    }
}
