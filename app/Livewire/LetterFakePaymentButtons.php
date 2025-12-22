<?php

namespace App\Livewire;

use Livewire\Component;
use App\Contracts\Cart;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Helpers\Accounting;
use \App\Models\Order;

class LetterFakePaymentButtons extends Component
{
    public $order;

    public function render()
    {
        return view('livewire.letter-fake-payment-buttons');
    }

    public function mount(): void {
        $cart = App::make(Cart::class);
        $order =  Order::find(Session::get('order_id'));
        $this->order = $order;
    }
}
