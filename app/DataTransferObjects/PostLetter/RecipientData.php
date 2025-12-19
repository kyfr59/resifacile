<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

class RecipientData extends Data
{
    /**
     * @param PaperAddress $paper_address
     * @param string $category
     * @param string $id
     * @param string|null $track_id
     * @param string|null $partner_track_id
     */
    public function __construct(
        #[Required]
        public PaperAddress $paper_address,
        #[Required]
        public string $category,
        #[Required]
        public string $id,
        #[Nullable,Size(32)]
        public ?string $track_id = null,
        #[Nullable,Size(38)]
        public ?string $partner_track_id = null,
    ){
    }
}
