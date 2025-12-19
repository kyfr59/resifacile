<?php

namespace App\Actions;

use App\DataTransferObjects\PostLetter\CampaignData;

class GetAllDocumentsFromCampaign
{
    /**
     * @param CampaignData $campaign
     * @return array
     */
    public function handle(CampaignData $campaign): array
    {
        $documents = [];

        foreach ($campaign->requests as $request) {
            foreach ($request->documentData as $document) {
                $documents[] = $document;
            }
        }

        return $documents;
    }
}
