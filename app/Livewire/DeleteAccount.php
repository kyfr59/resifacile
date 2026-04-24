<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class DeleteAccount extends Component
{
    public $showModal = false;
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:customers,email',
    ];

    protected $messages = [
        'email.required' => 'Vous devez renseigner votre email.',
        'email.email'    => "L'email n'est pas valide.",
        'email.exists'   => "Cet email n'est associé à aucun compte.",
    ];

    public function confirmDelete()
    {
        $this->validate();

        $customer = Customer::where('email', $this->email)->firstOrFail();

        // Log action
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'url' => request()->url(),
            ])
            ->event('delete_account')
            ->log('Le client a supprimé son compte');

        // Account deleting
        $customer->documents()->delete();
        $customer->delete();

        $this->showModal = false;

        // Redirects to homepage
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.delete-account');
    }
}
