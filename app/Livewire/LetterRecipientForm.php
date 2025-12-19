<?php

namespace App\Livewire;

use App\Contracts\Cart;
use App\Enums\AddressType;
use App\Helpers\Country;
use App\Traits\WithRecipients;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class LetterRecipientForm extends Component
{
    use WithRecipients;

    protected array $messages = [];

    protected array $validationAttributes = [];

    public bool $typeAddress = true;

    protected function rules(): array
    {
        $recipients = $this->recipientsRules();
        return array_merge($recipients);
    }

    public function mount(): void
    {
        $this->initRecipients();
        $this->updatedRecipients(true);
    }

    public function updatedTypeAddress(bool $value = false): void
    {
        if($value) {
            $this->recipients[0]['type'] = AddressType::PROFESSIONAL->value;
        } else {
            $this->recipients[0]['type'] = AddressType::PERSONAL->value;
        }
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        $cart = App::make(Cart::class);

        $cart->addRecipients($this->recipients);
        
        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client a renseigné les coordonnées destinataire');

        return redirect()->route('frontend.letter.sender');
    }

    public function render(): View
    {
        return view('livewire.letter-recipient-form', [
            'default_countries' => Country::default(),
            'countries' => Country::all(),
            'has_files' => App::make(Cart::class)->getOrder()->has_files,
        ]);
    }
}
