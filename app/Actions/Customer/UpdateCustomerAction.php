<?php

namespace App\Actions\Customer;

use App\Contracts\Cart;
use App\DataTransferObjects\CustomerData;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Support\Facades\App;

class UpdateCustomerAction
{
    /**
     * @param CustomerData $customerData
     * @return Customer
     */
    public function __invoke(CustomerData $customerData): Customer
    {
        $customer = Customer::updateOrCreate([
            'email' => $customerData->email,
        ], [
            'first_name' => $customerData->first_name,
            'last_name' => $customerData->last_name,
            'compagny' => $customerData->compagny,
            'phone' => $customerData->phone,
            'accept_gcs' => $customerData->customer_certifies_having_read_the_general_conditions_of_sale,
            'is_professional' => $customerData->is_professional,
            'accept_start_service' => $customerData->expressly_requests_the_start_of_the_service,
            'data' => [
                'partner' => session()->has('partner') ? session()->get('partner') : null
            ],
        ]);

        if($customer->addresses->count()) {
            $customer->addresses()
                ->where('is_billing_address', true)
                ->update([
                    'is_billing_address' => false
                ]);
        }

        $customer->addresses()->save(
            new Address(
                array_merge(
                    $customerData->billingAddress->toArray(),
                    ['is_billing_address', true]
                )
            )
        );

        return $customer;
    }
}
