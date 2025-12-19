<?php

namespace App\DataTransferObjects;

use App\Enums\ErrorType;
use App\Enums\NotificationStatus;
use Spatie\LaravelData\Data;

class Request extends Data
{
    public function __construct(
        public string $type,
        public string $track_id,
        public string $deposit_it,
        public string $reception_date,
        public string $gmt_reception_date,
        public NotificationStatus $status,
        public ?int $folds_count = null,
        public ?ErrorType $error_code = null,
        public ?string $error_label = null,
        public ?string $data = null,
    ){
    }
}
