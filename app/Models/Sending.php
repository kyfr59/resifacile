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
    ];

    protected $casts = [
        'data' => SendingData::class,
        'status' => SendingStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
