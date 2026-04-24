<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\DataTransferObjects\PostLetter\SendingData;
use App\Enums\SendingStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Sending extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'data',
        'provider_id',
        'status',
        'campaign_id',
        'maileva',
        'waiting_at',
        'sended_at',
        'accepted_at',
        'processed_at',
    ];

    protected $casts = [
        'data'         => SendingData::class,
        'status'       => SendingStatus::class,
        'maileva'      => 'array',
        'waiting_at'   => 'datetime',
        'sended_at'    => 'datetime',
        'accepted_at'  => 'datetime',
        'processed_at' => 'datetime',
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

    public static function fromMailevaId($mailevaId): ?static
    {
        return static::where('maileva->sending_id', $mailevaId)->first();
    }

    public function getMailevaSendingIdAttribute(): ?string
    {
        return $this->maileva['sending_id'] ?? null;
    }

    public function hasProofOfDeposit(): bool
    {
        $mailevaSendingId = $this->maileva['sending_id'] ?? null;

        if (! $mailevaSendingId) {
            return false;
        }

        return Storage::disk('local')->exists("proofs-of-deposit/{$mailevaSendingId}.pdf");
    }
}