<?php

namespace App\Livewire;

use App\DataTransferObjects\ContactData;
use App\Mail\ContactDemande;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $object = '';

    public string $message = '';

    PUBLIC bool $success = false;

    protected $rules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email',
        'object' => 'required',
        'message' => 'required',
    ];

    public function save(): void
    {
        $this->validate();

        Mail::to('contact@' . config('mail.from.name'))
            ->send(
                new ContactDemande(new ContactData(
                    first_name: $this->first_name,
                    last_name: $this->last_name,
                    email: $this->email,
                    object: $this->object,
                    message: $this->message,
                    phone: $this->phone,
                ))
            );

        $this->reset();
        $this->success = true;

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
            ])
            ->event('onClick')
            ->log('Le client a envoyé un message à ' . config('mail.from.address'));
    }

    public function render()
    {
        return view('livewire.contact-form', ['objects' => ['Assistance commande', 'Demande d\'informations', 'Problème technique', 'Réclamation', 'Désabonnement']]);
    }
}
