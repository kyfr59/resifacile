<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class OptionData extends Data
{
    /**
     * @param PaperOptionData|null $request_option
     * @param DataCollection|null $document_options
     * @param PaperOptionData|null $page_options
     */
    public function __construct(
        #[Nullable]
        public ?PaperOptionData $request_option = null,
        #[DataCollectionOf(DocumentOptionData::class)]
        public ?DataCollection $document_options = null,
        #[Nullable]
        public ?PaperOptionData $page_options = null,
    ){
    }
}
