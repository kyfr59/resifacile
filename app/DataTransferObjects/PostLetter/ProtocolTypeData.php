<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class ProtocolTypeData extends Data
{
    /**
     * @param string|null $attribute
     * @param string|null $value
     */
    public function __construct(
        #[Nullable]
        public ?string $attribute = null,
        #[Nullable]
        public ?string $value = null,
    ){
    }
}
