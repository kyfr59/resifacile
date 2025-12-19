<?php

namespace App\Livewire;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChangeProfileInformationFrom extends Component
{
    public Customer $customer;

    protected $validationAttributes = [
        'customer.first_name' => 'prénom',
        'customer.last_name' => 'nom',
        'customer.compagny' => 'raison social',
        'customer.phone' => 'Téléphone',
    ];

    protected function rules(): array
    {
        $rules = [
            'customer.first_name' => 'required|max:19',
            'customer.last_name' => 'required|max:19',
            'customer.email' => 'required|email',
            'customer.phone' => '',
        ];

        if ($this->customer->is_professional) {
            $rules['customer.compagny'] = 'required|string|max:38';
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->customer = Auth::guard('site')->user();
    }

    public function save(): void
    {
        $this->validate();
        $this->customer->save();

        //Auth::guard('site')->user()->email = $this->customer->email;
        //Auth::guard('site')->user()->first_name = $this->customer->first_name;
        //Auth::guard('site')->user()->last_name = $this->customer->last_name;
        //Auth::guard('site')->user()->save();
        Auth::guard('site')->user()->refresh();

        session()->flash('message', 'Votre profile à bien été mis à jour !');
    }

    public function render(): View
    {
        return view('livewire.change-profile-information-from');
    }
}
