<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use App\Enums\SubscriptionStatus;
use App\Mail\UnsubscribeDemande;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use App\Actions\Subscription\UnsubscribedProcessAction;

class DeleteSubscription extends Component
{
    public $showModal = false;
    public $email;
    public $subscriptionIsActive;

    protected $rules = [
        'email' => 'required|email|exists:customers,email',
    ];

    protected $messages = [
        'email.required' => 'Vous devez renseigner votre email.',
        'email.email'    => "L'email n'est pas valide.",
        'email.exists'   => "Cet email n'est associé à aucun compte.",
    ];

    public function mount() {
        $user = Auth::user();
        $this->subscriptionIsActive  = $user->subscription->status !== SubscriptionStatus::CANCELED;
    }

    public function confirmDelete()
    {
        $this->validate();

        $customer = Customer::where('email', $this->email)->first();

        if(
            $customer->subscription &&
            $customer->subscription->status !== SubscriptionStatus::CANCELED
        ) {

            if(property_exists($customer->subscription->meta_data, 'mid')) {
                (new UnsubscribedProcessAction($customer->subscription))->process();
            } else {
                $paymentMethod = $paymentGateway->get('stripe');
                $subscription = $paymentMethod->cancelSubscription($subscription->meta_data->subscription_id);
            }

            activity()
                ->withProperties([
                    'ip' => session()->get('ipClient'),
                    'url' => request()->url()
                ])
                ->event('onClick')
                ->log('Le client a fait une demande de désabonnement depuis son espace client');
        }

        return redirect()
            ->route('auth.account')
            ->with('unsubscribe-message', "Votre abonnement Accès+ a été résilié.\n\nSi vous avez des questions, contactez notre service client par téléphone au 0 805 080 190.");
    }

    public function render()
    {
        return view('livewire.delete-subscription');
    }
}
