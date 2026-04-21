<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookCall extends Model
{
    protected $table = 'webhook_calls';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Extrait une valeur du payload JSON.
     */
    public function getPayloadValue(string $key): mixed
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Accesseurs pratiques pour les champs Maileva
     */
    public function getResourceIdAttribute(): ?string
    {
        return $this->payload['resource_id'] ?? null;
    }

    public function getEventTypeAttribute(): ?string
    {
        return $this->payload['event_type'] ?? null;
    }

    public function getResourceCustomIdAttribute(): ?string
    {
        return $this->payload['resource_custom_id'] ?? null;
    }
}