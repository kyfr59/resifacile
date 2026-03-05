<?php

namespace App\Contracts;

use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\RequestData;
use App\Enums\PostageType;
use App\Models\Sending;
use Spatie\LaravelData\DataCollection;

interface PostLetter
{
    /**
     * @return SendingData
     */
    public function newSending(): SendingData;

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
     * @param SendingData $sending
     * @return void
     */
    public function pushSending(SendingData $sending): void;
}
