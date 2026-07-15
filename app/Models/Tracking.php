<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';

    protected $fillable = [
        'id_ship', 'last_event_code', 'last_event_date', 'is_final', 'sending_id',
    ];

    protected $casts = [
        'last_event_date' => 'datetime',
        'is_final' => 'boolean',
    ];

    public function sending(): BelongsTo
    {
        return $this->belongsTo(Sending::class);
    }
}