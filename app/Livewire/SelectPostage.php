<?php

namespace App\Livewire;

use App\Contracts\Cart;
use App\Enums\PostageType;
use App\Helpers\Accounting;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class SelectPostage extends Component
{
    public bool $postagePage = false;

    public function setSimpleLetter(): void
    {
        $this->setOffer(PostageType::GREEN_LETTER);

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'has_subscription' => false
            ])
            ->event('onClick')
            ->log('Le client a choisi la lettre recommandée');

        $this->redirection();
    }

    public function setFollowedLetter(): void
    {
        $this->setOffer(PostageType::FOLLOWED_LETTER);

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'has_subscription' => false
            ])
            ->event('onClick')
            ->log('Le client a choisi la lettre suivie');

        $this->redirection();
    }

    public function setRegisteredLetter(): void
    {
        $this->setOffer(PostageType::REGISTERED_LETTER);

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'has_subscription' => true
            ])
            ->event('onClick')
            ->log('Le client a choisi la lettre recommandée');

        $this->redirection();
    }

    private function setOffer(PostageType $postageType): void
    {
        $cart = App::make(Cart::class);
        $order = $cart->getOrder();
        $order->postage = $postageType;
        $order->has_subscription = false;
        $order->with_subscription = false;
        $cart->addOrder($order);
    }

    private function redirection(): RedirectResponse|Redirector
    {
        if($this->postagePage) {
            return redirect()->route('frontend.letter.validation');
        }

        return redirect()->route('frontend.letter.select');
    }

    public function render(): view
    {
        return view('livewire.select-postage', [
            'pricing' => App::make(pricingSettings::class),
            'subscription' => App::make(subscriptionSettings::class),
            'accounting' => App::make(accounting::class),
        ]);
    }
}
