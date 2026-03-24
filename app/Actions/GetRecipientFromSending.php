<?php

namespace App\Actions;

use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\RecipientData;

class GetRecipientFromSending
{
    /**
     * @param SendingData $sending
     * @return array
     */
    public function handle(SendingData $sending): RecipientData
    {
        return $sending->requests[0]->recipients[0];
    }
}
