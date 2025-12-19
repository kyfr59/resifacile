<?php

namespace App\DataTransferObjects;

use App\Enums\NotificationType;
use SimpleXMLElement;
use Spatie\LaravelData\Data;

class NotificationData extends Data
{
    public function __construct(
        public NotificationType $notificationType,
        public array            $request,
    )
    {
    }

    /**
     * @param string $notification
     * @return static
     */
    public static function fromXML(SimpleXMLElement $value): self
    {
        $data = [];

        if (dom_import_simplexml($value->Request->PaperOptions->PostageClass)->nodeValue === 'LRE_AR') {
            $data = ['Erl_numbers' => dom_import_simplexml($value->Request->ErlNumbers)->nodeValue];
        }

        return new self(
            notificationType: NotificationType::GENERAL,
            request: [
                'type' => dom_import_simplexml($value->Request->Type)->nodeValue,
                'track_id' => dom_import_simplexml($value->Request->TrackId)->nodeValue,
                'deposit_it' => dom_import_simplexml($value->Request->DepositId)->nodeValue,
                'reception_date' => dom_import_simplexml($value->Request->ReceptionDate)->nodeValue,
                'gmt_reception_date' => dom_import_simplexml($value->Request->GmtReceptionDate)->nodeValue,
                'status' => dom_import_simplexml($value->Request->Status)->nodeValue,
                'data' => $data,
            ],
        );
    }
}
