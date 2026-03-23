<?php

namespace App\Actions;

use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\DocumentData;

class GetDocumentFromSending
{
    /**
     * @param CampaignData $campaign
     * @return array
     */
    public function handle(SendingData $sending): DocumentData
    {
        $documents = [];

        foreach ($sending->requests as $request) {
            foreach ($request->documentData as $document) {
                $documents[] = $document;
            }
        }

        return $documents[0];
    }
}
