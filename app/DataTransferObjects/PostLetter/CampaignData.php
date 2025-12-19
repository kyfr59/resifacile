<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CampaignData extends Data
{
    /**
     * @param UserData $user
     * @param DataCollection $requests
     * @param string $version
     * @param string $partner_track_id
     * @param string|null $name
     * @param string|null $track_id
     * @param string|null $com_application_name
     * @param string|null $break_down_code
     */
    public function __construct(
        #[Required]
        public UserData $user,
        #[Required,DataCollectionOf(RequestData::class)]
        public DataCollection $requests,
        #[Required]
        public string $version,
        #[Required]
        public string $partner_track_id,
        #[Nullable]
        public ?string $name = null,
        #[Nullable]
        public ?string $track_id = null,
        #[Nullable]
        public ?string $com_application_name = null,
        #[Nullable]
        public ?string $break_down_code = null,
    ){
    }
}
