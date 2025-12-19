<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class FoldData extends Data
{
    /**
     * @param string $id
     * @param string $recipient_id
     * @param string|null $sender_id
     * @param string|null $track_id
     */
    public function __construct(
        #[Required]
        public string $id,
        #[Required]
        public string $recipient_id,
        #[Nullable]
        public ?string $sender_id = null,
        #[Nullable]
        public ?string $track_id = null,
    ){
    }
}
