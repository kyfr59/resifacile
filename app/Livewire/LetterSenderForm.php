<?php

namespace App\Livewire;

use App\Contracts\Cart;
use App\Contracts\Pdf;
use App\DataTransferObjects\AddressData;
use App\DataTransferObjects\CustomerData;
use App\Enums\AddressType;
use App\Helpers\Country;
use App\Traits\WithSenders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class LetterSenderForm extends Component
{
    use WithSenders;

    protected array $messages = [];

    protected array $validationAttributes = [];

    public ?string $phone = null;

    public ?string $email = null;

    public bool $typeAddress = false;

    protected function rules(): array
    {
        $senders = $this->sendersRules();
        return array_merge($senders);
    }

    public function mount(): void
    {
        $this->initSenders();
        $this->updatedTypeAddress(false);

        $customer =  App::make(Cart::class)->getCustomer();
        $this->email = $customer->email;
        $this->phone = $customer->phone;
    }

    public function updatedTypeAddress(bool $value = false): void
    {
        if($value) {
            $this->senders[0]['type'] = AddressType::PROFESSIONAL->value;
        } else {
            $this->senders[0]['type'] = AddressType::PERSONAL->value;
        }
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        $cart = App::make(Cart::class);

        $cart->addSenders($this->senders);

        $customer = $cart->getCustomer();

        if($customer->phone) {
            $phone = $customer->phone;
        } else {
            $phone = null;
        }

        $cart->addCustomer(new CustomerData(
            first_name: $this->senders[0]['first_name'],
            last_name: $this->senders[0]['last_name'],
            compagny: ($this->senders[0]['type'] === AddressType::PROFESSIONAL->value) ? $this->senders[0]['compagny'] : null,
            email: $this->email,
            phone: $phone,
            billingAddress: AddressData::fromArray($this->senders[0]),
            is_professional: $this->senders[0]['type'] === AddressType::PROFESSIONAL->value,
        ));

        (App::make(Pdf::class))->makeAll();

        if(App::make(Cart::class)->getOrder()->init_postage_selection) {
            return redirect()->route('frontend.letter.validation');
        }

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client a renseigné les coordonnées expéditeur');

        return redirect()->route('frontend.letter.postage');
    }

    public function render()
    {
        return view('livewire.letter-sender-form', [
            'default_countries' => Country::default(),
            'countries' => Country::all()
        ]);
    }
}
