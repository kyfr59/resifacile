<?php

namespace App\DataTransferObjects;

use App\DataTransferObjects\Casts\AddressTypeCast;
use App\Enums\AddressType;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class AddressData extends Data
{
    /**w
     * @param string|null $compagny
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string|null $address_line_2
     * @param string|null $address_line_3
     * @param string|null $address_line_4
     * @param string|null $address_line_5
     * @param string|null $postal_code
     * @param string|null $city
     * @param string|null $country
     * @param AddressType|null $type
     */
    public function __construct(
        public ?string $compagny,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $address_line_2,
        public ?string $address_line_3,
        public ?string $address_line_4,
        public ?string $address_line_5,
        public ?string $postal_code,
        public ?string $city,
        public ?string $country,
        public ?string $country_code,
        #[WithCast(AddressTypeCast::class)]
        public ?AddressType $type,
    ){}

    public static function fromArray(array $address): self
    {
        if(array_key_exists('country', $address) && Str::contains( $address['country'],'_')) {
            $countryArray = explode('_', $address['country']);

            $address['country'] = $countryArray[0];
            $address['country_code'] = $countryArray[1];
        } else {
            $address['country'] = 'FRANCE';
            $address['country_code'] = 'FR';
        };

        if(array_key_exists('type', $address) && is_string($address['type'])) {
            $address['type'] = (AddressType::PERSONAL->value === $address['type']) ? AddressType::PERSONAL : AddressType::PROFESSIONAL;
        } else {
            $address['type'] = AddressType::PROFESSIONAL;
        }

        return new self(
            compagny: $address['compagny'],
            first_name: $address['first_name'] ?? '',
            last_name: $address['last_name'] ?? '',
            address_line_2: $address['address_line_2'],
            address_line_3: $address['address_line_3'],
            address_line_4: $address['address_line_4'],
            address_line_5: $address['address_line_5'],
            postal_code: $address['postal_code'],
            city: $address['city'],
            country: $address['country'],
            country_code: $address['country_code'],
            type: $address['type'],
        );
    }

}
