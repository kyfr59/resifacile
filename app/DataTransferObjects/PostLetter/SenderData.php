<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class SenderData extends Data
{
    /**
     * @param PaperAddress $paper_address
     * @param string $id
     */
    public function __construct(
        #[Required]
        public PaperAddress $paper_address,
        #[Required]
        public string $id,
    ){
    }
}
