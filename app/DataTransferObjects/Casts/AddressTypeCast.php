<?php

namespace App\DataTransferObjects\Casts;

use App\Enums\AddressType;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class AddressTypeCast implements Cast
{

    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return AddressType::from($value);
    }
}
