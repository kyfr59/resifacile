<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class ContactData extends Data
{
    public function __construct(
        #[Required]
        public string $first_name,
        #[Required]
        public string $last_name,
        #[Required]
        public string $email,
        #[Required]
        public string $object,
        #[Required]
        public string $message,
        public ?string $phone = null,
    ){
    }
}
