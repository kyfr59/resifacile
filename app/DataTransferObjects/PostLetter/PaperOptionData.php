<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class PaperOptionData extends Data
{
    /**
     * @param FoldOptionPaperData|null $fold_option
     * @param bool $stamp_adjust
     * @param bool $documents_restitution
     */
    public function __construct(
        #[Nullable]
        public ?FoldOptionPaperData $fold_option = null,
        #[BooleanType]
        public bool $stamp_adjust = false,
        #[BooleanType]
        public bool $documents_restitution = false,
    ){
    }
}
