<?php

namespace App\Livewire;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChangeProfileInformationFrom extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $compagny;

    public Customer $customer;

    protected $validationAttributes = [
        'first_name' => 'prénom',
        'last_name' => 'nom',
        'compagny' => 'raison social',
        'phone' => 'Téléphone',
    ];

    protected function rules(): array
    {
        $rules = [
            'first_name' => 'required|max:19',
            'last_name'  => 'required|max:19',
            'email'      => 'required|email',
            'phone'      => '',
        ];

        if ($this->customer->is_professional) {
            $rules['compagny'] = 'required|string|max:38';
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->customer = Auth::guard('site')->user();
        if (! $this->customer) {
            abort(403, 'Utilisateur non connecté.');
        }

        $this->first_name = $this->customer->first_name;
        $this->last_name = $this->customer->last_name;
        $this->email = $this->customer->email;
        $this->phone = $this->customer->phone;
        $this->compagny = $this->customer->compagny;
    }

    public function save(): void
    {
        $this->validate();

        $this->customer->first_name = $this->first_name;
        $this->customer->last_name  = $this->last_name;
        $this->customer->email      = $this->email;
        $this->customer->phone      = $this->phone;
        $this->customer->compagny   = $this->compagny;
        $this->customer->save();

        session()->flash('message', 'Votre profil a bien été mis à jour !');
    }

    public function render(): View
    {
        return view('livewire.change-profile-information-from');
    }
}
