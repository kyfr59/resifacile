<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'from',
        'subject',
        'body',
        'type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
