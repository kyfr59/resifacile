<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class ContentData extends Data
{
    /**
     * @param string $uri
     */
    public function __construct(
        #[Required]
        public string $uri,
    ){
    }
}
