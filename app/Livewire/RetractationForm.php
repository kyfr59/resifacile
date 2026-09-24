<?php

namespace App\Livewire;

use App\Enums\SubscriptionStatus;
use App\Mail\UnsubscribeDemande;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class RetractationForm extends Component
{
    public ?string $email= '';

    public bool $success = false;

    public bool $error = false;

    public string $message = '';

    public ?string $debugMessage = '';

    protected $messages = [
        'email.required' => 'Vous devez renseigner votre email.',
        'email.email'    => "L'email n'est pas valide.",
        'email.exists'   => "Nous n’avons trouvé aucun abonnement associé à cette adresse email. Veuillez vérifier votre saisie ou contacter notre service client par téléphone au 0805 690 500, du lundi au vendredi de 9h30 à 17h, ou depuis notre page de contact.",
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

                    if ( ! $customer || ! $customer->subscription) {
                        $this->debugMessage = '';
                        $fail("Nous n’avons trouvé aucun abonnement associé à cette adresse email. Veuillez vérifier votre saisie ou contacter notre service client par téléphone au 0805 690 500, du lundi au vendredi de 9h30 à 17h, ou depuis notre page de contact.");
                        return;
                    }

                    if ($customer->subscription->status === SubscriptionStatus::CANCELED) {
                        $this->debugMessage = '';
                        $fail(
                            "Votre abonnement a été annulé le "
                            . $customer->subscription->cancellation_request_at->format('d/m/Y')
                            . ". Si vous avez des questions, contactez notre service client par téléphone au 0 805 690 500."
                        );
                    }

                    $dateCreation = $customer->subscription->created_at;
                    $firstDay = $dateCreation->copy()->addDay();
                    $limitDay = $dateCreation->copy()->addDays(13);

                    $joursFeries = [
                        '2026-01-01',
                        '2026-04-03',
                        '2026-04-06',
                        '2026-05-14',
                        '2026-05-25',
                        '2026-08-01',
                        '2026-12-25',
                    ];

                    while (
                        $limitDay->isWeekend() ||
                        in_array($limitDay->toDateString(), $joursFeries)
                    ) {
                        $limitDay->addDay();
                    }

                    $realLimitDate = $limitDay->copy()->endOfDay(); // Set 23:59:59

                    if (filament()->auth()->check()) {
                        $d  = "<b>Date de l'abonnement stocké en base</b> : <br />".$dateCreation;
                        $d .= "<br /><b>Premier jour :</b> <br />".$firstDay;
                        $d .= "<br /><b>Date limite :</b> <br />".$dateCreation->copy()->addDays(12);
                        $we = in_array($limitDay->toDateString(), $joursFeries) ? 'oui' : 'non';
                        $d .= "<br /><b>Week-end ou férié ?</b> <br />" . $we;
                        $d .= "<br /><b>Vraie date limite :</b> <br />".$limitDay;
                        $d .= "<br /><b>Vraie date limite à minuit (finale) :</b> <br />".$realLimitDate;
                        $retractionPossible = $realLimitDate->isFuture() ? 'oui' : 'non';
                        $now = now('Europe/Paris');
                        $d .= "<br /><b>Encore temps ?</b> (".$now.") <br />".$retractionPossible;
                        $this->debugMessage = $d;
                    }

                    if ($realLimitDate->isPast()) {
                        $fail("Nous avons trouvé un abonnement associé à cette adresse email. Cependant, le délai permettant d’exercer votre droit de rétractation est désormais dépassé.<br /><br />Si vous souhaitez mettre fin à votre abonnement et empêcher tout nouveau prélèvement, rendez-vous sur notre page de résiliation.<br /><br /><a href='/se-desabonner' class='w-full md:w-auto h-12 px-4 border-2 border-blue-700 text-blue-700 rounded-xl text-base md:text-sm inline-flex items-center justify-center'>Résilier mon abonnement</a>");
                        return;
                    }
                },
            ],
        ];
    }

    public function save(): void
    {
        $this->success = false;
        $this->error = false;
        $this->message = '';

        $this->validate();

        $customer = Customer::where('email', $this->email)->first();

        if(
            $customer->subscription &&
            $customer->subscription->status !== SubscriptionStatus::CANCELED
        ) {
            Mail::to($this->email)->send(new UnsubscribeDemande($this->email));

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
            $this->error = true;
            $this->message = "Nous n'avons pas trouver d'abonnement lié avec votre email. Contactez notre service client par téléphone au 0 805 690 500.";
        }
    }

    public function render(): View
    {
        return view('livewire.retractation-form');
    }
}
