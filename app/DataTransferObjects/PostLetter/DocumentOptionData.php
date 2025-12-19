<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DocumentOptionData extends Data
{
    /**
     * @param string $id
     * @param DocumentOptionPaperData $paper_option
     */
    public function __construct(
        #[Required]
        public string $id,
        #[Required]
        public DocumentOptionPaperData $paper_option,
    ){
    }
}
