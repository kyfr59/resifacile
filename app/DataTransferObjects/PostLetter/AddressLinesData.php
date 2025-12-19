<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

class AddressLinesData extends Data
{
    /**
     * @param string|null $address_line_1
     * @param string|null $address_line_2
     * @param string|null $address_line_3
     * @param string|null $address_line_4
     * @param string|null $address_line_5
     * @param string|null $address_line_6
     */
    public function __construct(
        #[Nullable,Size(38)]
        public ?string $address_line_1 = null,
        #[Nullable,Size(38)]
        public ?string $address_line_2 = null,
        #[Nullable,Size(38)]
        public ?string $address_line_3 = null,
        #[Nullable,Size(38)]
        public ?string $address_line_4 = null,
        #[Nullable,Size(38)]
        public ?string $address_line_5 = null,
        #[Nullable,Size(38)]
        public ?string $address_line_6 = null,
    ){
    }
}
