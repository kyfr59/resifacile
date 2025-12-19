<?php

namespace App\Contracts;

use App\DataTransferObjects\PostLetter\CampaignData;
use App\DataTransferObjects\PostLetter\RequestData;
use App\Enums\PostageType;
use App\Models\Campaign;
use Spatie\LaravelData\DataCollection;

interface PostLetter
{
    /**
     * @return CampaignData
     */
    public function newCampaign(): CampaignData;

    /**
     * @param DataCollection $recipients
     * @param DataCollection $senders
     * @param DataCollection $documents
     * @param DataCollection $options
     * @return RequestData
     */
    public function newRequest(
        String $trackId,
        PostageType $postageType,
        DataCollection $recipients,
        DataCollection $senders,
        DataCollection $documents,
        DataCollection $options
    ): RequestData;

    /**
     * @param CampaignData $campaign
     * @return void
     */
    public function pushCampaign(CampaignData $campaign): void;
}
