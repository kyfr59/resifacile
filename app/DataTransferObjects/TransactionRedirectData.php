<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class TransactionRedirectData extends Data
{
    /**
     * @param int $status
     * @param string|null $url
     * @param string|null $message
     */
    public function __construct(
        #[Required]
        public int $status,
        #[Required]
        public ?string $url = null,
        #[Nullable]
        public ?string $message = null,
    ){
    }
}
