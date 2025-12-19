<?php

namespace App\Actions\Billing;

use App\DataTransferObjects\AddressData;
use App\Models\Customer;
use Illuminate\Support\Arr;

class CreateBillingAddressAction
{
    public function __invoke(
        AddressData $addressData,
        Customer $customer,
    ): void
    {
        if($customer->addresses->count()) {
            $customer->addresses()
                ->where('is_billing_address', true)
                ->update([
                    'is_billing_address' => false
                ]);
        }

        $customer->addresses()->updateOrCreate(
            $addressData->toArray(),
            ['is_billing_address' => true]
        );
    }
}
