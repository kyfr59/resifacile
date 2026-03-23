<?php

namespace App\Actions;

use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\SenderData;

class GetSenderFromSending
{
    /**
     * @param SendingData $sending
     * @return array
     */
    public function handle(SendingData $sending): SenderData
    {
        return $sending->requests[0]->senders[0];
    }
}
