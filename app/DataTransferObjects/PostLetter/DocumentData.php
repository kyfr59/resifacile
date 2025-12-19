<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DocumentData extends Data
{
    /**
     * @param string $id
     * @param ContentData $content
     * @param bool $shrink
     * @param int|null $size
     */
    public function __construct(
        #[Required]
        public string $id,
        #[Required]
        public ContentData $content,
        #[BooleanType]
        public bool $shrink = false,
        #[Nullable]
        public ?int $size = null,
    ){
    }
}
