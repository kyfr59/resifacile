<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'compagny',
        'first_name',
        'last_name',
        'email',
        'phone',
        'is_professional',
        'accept_gcs',
        'accept_start_service',
        'data',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'is_professional' => 'boolean',
        'accept_gcs' => 'boolean',
        'accept_start_service' => 'boolean',
        'data' => 'object',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => Str::of($attributes['first_name'] . ' ' . $attributes['last_name'])->title(),
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Str::of($value)->title()->value(),
            set: fn(?string $value) => Str::of($value)->lower()->value(),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Str::of($value)->title()->value(),
            set: fn(?string $value) => Str::of($value)->lower()->value(),
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::of($value)->lower()->value(),
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function campaign(): HasOne
    {
        return $this->hasOne(Campaign::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function routeNotificationForVonage(): string
    {
        return '+33' . Str::substr($this->phone, 1);
    }

    public function mailRecieves(): HasMany
    {
        return $this->hasMany(MailReceive::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
