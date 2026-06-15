<?php

namespace App\Actions;

use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\DocumentData;

class GetDocumentsFromSending
{
    /**
     * @param SendingData $sending
     * @return array
     */
    public function handle(SendingData $sending): array
    {
        $documents = [];

        foreach ($sending->requests as $request) {
            foreach ($request->documentData as $document) {
                $documents[] = $document;
            }
        }

        return $documents;
    }
}
