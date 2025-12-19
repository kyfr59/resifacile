<?php

namespace App\DataTransferObjects;

use App\Enums\DocumentType;
use App\Enums\PostageClassType;
use App\Enums\PostageType;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class OrderData extends Data
{
    public function __construct(
        #[DataCollectionOf(OptionData::class)]
        public DataCollection $options,
        public ?PostageType $postage = null,
        public int $vat_rate = 0,
        public float $amount = 0.0,
        public bool $customer_certifies_documents_are_compliant = false,
        public bool $has_subscription = false,
        public bool $with_subscription = false,
        public bool $has_files = false,
        public bool $init_postage_selection = false,
        public ?DocumentType $document_type = null,
    ){
    }
}
