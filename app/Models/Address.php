<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'compagny',
        'first_name',
        'last_name',
        'address_line_2',
        'address_line_3',
        'address_line_4',
        'address_line_5',
        'postal_code',
        'city',
        'country',
        'country_code',
        'type',
        'is_billing_address',
        'updated_at',
        'created_at',
        'customer_id',
    ];

    protected $casts = [
        'type' => AddressType::class,
        'is_billing_address' => 'boolean',
    ];

    protected function full(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if($attributes['type'] === AddressType::PROFESSIONAL->value) {
                    $address = $attributes['compagny'];
                } else {
                    $address = $attributes['first_name'] . ' ' . $attributes['last_name'];
                }

                return $address . ', ' . $attributes['address_line_4'] . ', ' . $attributes['postal_code'] . ' ' . $attributes['city'];
            },
        );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
