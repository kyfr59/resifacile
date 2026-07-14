<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';

    protected $fillable = [
        'id_ship', 'last_event_code', 'last_event_date', 'is_final',
    ];

    protected $casts = [
        'last_event_date' => 'datetime',
        'is_final' => 'boolean',
    ];
}