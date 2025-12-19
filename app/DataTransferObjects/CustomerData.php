<?php

namespace App\DataTransferObjects;

use App\Enums\AddressType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class CustomerData extends Data
{
    /**
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string|null $email
     * @param string|null $phone
     * @param bool $customer_certifies_having_read_the_general_conditions_of_sale
     * @param bool $expressly_requests_the_start_of_the_service
     * @param array|null $meta_data
     */
    public function __construct(
        #[Required,Nullable]
        public ?string $first_name,
        #[Required,Nullable]
        public ?string $last_name,
        #[Nullable]
        public ?string $compagny,
        #[Required,Email,Nullable]
        public ?string $email,
        #[Nullable]
        public ?string $phone = null,
        #[Nullable]
        public ?AddressData $billingAddress = null,
        #[BooleanType]
        public bool $is_professional = false,
        #[Required,BooleanType]
        public bool $customer_certifies_having_read_the_general_conditions_of_sale = false,
        #[Required,BooleanType]
        public bool $expressly_requests_the_start_of_the_service = false,
        #[Nullable]
        public ?array $meta_data = null,
    ){
    }
}
