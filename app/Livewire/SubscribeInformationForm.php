<?php

namespace App\Livewire;

use App\Models\Subscription;
use Livewire\Component;

class SubscribeInformationForm extends Component
{
    public Subscription $subscription;

    public function mount(Subscription $subscription): void
    {
        $this->subscription = auth('site')->user()->subscription;
    }

    public function render()
    {
        return view('livewire.subscribe-information-form', [
            'subscription' => $this->subscription,
        ]);
    }
}
