<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class DocumentOptionPaperData extends Data
{
    /**
     * @param PaperOptionData|null $page_option
     * @param bool $print_duplex
     */
    public function __construct(
        #[Nullable]
        public ?PaperOptionData $page_option = null,
        #[BooleanType]
        public bool $print_duplex = false,
    ){
    }
}
