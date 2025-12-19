<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

class PaperAddress extends Data
{
    /**
     * @param AddressLinesData $address_lines
     * @param string|null $country
     * @param string|null $country_code
     */
    public function __construct(
        #[Required]
        public AddressLinesData $address_lines,
        #[Nullable,Size(32)]
        public ?string $country = null,
        #[Nullable,Size(2)]
        public ?string $country_code = null,
    ){
    }
}
