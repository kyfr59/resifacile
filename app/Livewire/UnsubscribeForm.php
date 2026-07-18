<?php

namespace App\Livewire;

use App\Enums\SubscriptionStatus;
use App\Mail\UnsubscribeDemande;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class UnsubscribeForm extends Component
{
    public ?string $email= '';

    public bool $success = false;

    public bool $error = false;

    public string $message = '';

    protected $messages = [
        'email.required' => 'Vous devez renseigner votre email.',
        'email.email'    => "L'email n'est pas valide.",
        'email.exists'   => "Nous n'avons pas trouvé d'abonnement lié à votre email. Contactez notre service client par téléphone au 0 805 080 190 ou via notre <a style='text-decoration:underline' href='/contact'>page de contact</a>.",
    ];

    protected function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:customers,email',
                function ($attribute, $value, $fail) {
                    $customer = Customer::where('email', $value)->first();

                    if (! $customer || ! $customer->subscription) {
                        $fail("Nous n'avons pas trouvé d'abonnement lié à votre email. Contactez notre service client par téléphone au 0 805 080 190.");
                        return;
                    }

                    if ($customer->subscription->status === SubscriptionStatus::CANCELED) {
                        $fail(
                            "Votre abonnement a été annulé le "
                            . $customer->subscription->cancellation_request_at->format('d/m/Y')
                            . ". Si vous avez des questions, contactez notre service client par téléphone au 0 805 080 190."
                        );
                    }
                },
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $customer = Customer::where('email', $this->email)->first();

        if(
            $customer->subscription &&
            $customer->subscription->status !== SubscriptionStatus::CANCELED
        ) {
            Mail::to($this->email)->send(new UnsubscribeDemande($this->email));

            $this->reset();
            $this->success = true;

            activity()
                ->withProperties([
                    'ip' => session()->get('ipClient'),
                    'url' => request()->url()
                ])
                ->event('onClick')
                ->log('Le client a fait une demande de désabonnement');
        } else if (
            $customer->subscription &&
            $customer->subscription->status === SubscriptionStatus::CANCELED
        ) {
            $this->error = true;
            $this->message = "Votre abonnement a été annulé le " . $customer->subscription->cancellation_request_at->format('d/m/Y') . ". Si vous avez des questions, contactez notre service client par téléphone au 0 805 080 190.";
        } else {
            dd("dd");
            $this->error = true;
            $this->message = "Nous n'avons pas trouver d'abonnement lié avec votre email. Contactez notre service client par téléphone au 0 805 080 190.";
        }
    }

    public function render(): View
    {
        return view('livewire.unsubscribe-form');
    }
}
