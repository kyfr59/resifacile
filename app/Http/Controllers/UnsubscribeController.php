<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\UnsubscribedProcessAction;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Mail\UnsubcribeConfirmation;
use App\Models\Subscription;
use App\Models\Unsubscribe;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UnsubscribeController extends Controller
{
    public function __invoke(Request $request, PaymentGatewayRegistry $paymentGateway)
    {
        if($request->has('token')) {
            $unsubscribe = Unsubscribe::where('token', $request->get('token'))->first();

            if($unsubscribe) {
                $id = $unsubscribe->customer->subscription->id;

                $unsubscribe->delete();

                $subscription = Subscription::find($id);

                if($subscription->status !== SubscriptionStatus::CANCELED) {
                    if(property_exists($subscription->meta_data, 'mid')) {
                        (new UnsubscribedProcessAction($subscription))->process();
                    } else {
                        $paymentMethod = $paymentGateway->get('stripe');
                        $subscription = $paymentMethod->cancelSubscription($subscription->meta_data->subscription_id);
                    }

                    try {
                        Mail::to($unsubscribe->customer->email)->send(new UnsubcribeConfirmation($subscription));
                    } catch (\Exception $e) {
                        Log::error($e);
                    }

                    activity()
                        ->withProperties([
                            'ip' => session()->get('ipClient'),
                            'url' => request()->url()
                        ])
                        ->event('onClick')
                        ->log('Le client c\'est désabonné');

                    redirect()
                        ->route('pages.se-desabonner')
                        ->with('message', 'Nous vous confirmons que nous avons bien enregistré votre demande et que votre résiliation a bien été effectuée.');
                }

                redirect()
                    ->route('pages.se-desabonner')
                    ->with('message', 'Votre abonnement a été annulé le ' . $subscription->cancellation_request_at->format('d/m/Y') . '. Si vous avez des questions, contactez notre service client par téléphone au 0 805 080 190.');
            }
        }

        return redirect()
            ->route('pages.se-desabonner')
            ->with('error', "Vous n'avez pas l'authorisation d'accéder à cette page");
    }
}
