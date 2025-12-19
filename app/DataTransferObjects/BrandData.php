<?php

namespace App\DataTransferObjects;

use App\Enums\PageStatus;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class BrandData extends Data
{
    /**
     * @param string $name
     * @param AddressData|null $address
     * @param PageStatus $status
     */
    public function __construct(
        #[Required]
        public string $name,
        #[Nullable]
        public ?AddressData $address,
        #[Required]
        public PageStatus $status,
    ){
    }
}
