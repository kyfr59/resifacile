<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class NotificationData extends Data
{
    /**
     * @param string $type
     * @param string $format
     * @param DataCollection $protocols
     */
    public function __construct(
        #[Required]
        public string $type,
        #[Required]
        public string $format,
        #[Required,DataCollectionOf(ProtocolData::class)]
        public DataCollection $protocols,
    ){
    }
}
