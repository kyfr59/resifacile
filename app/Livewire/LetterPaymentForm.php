<?php

namespace App\Livewire;

use App\Actions\Billing\CreateBillingAddressAction;
use App\Actions\Customer\CreateCustomerAction;
use App\Actions\Customer\UpdateCustomerAction;
use App\Actions\MakeNewOrder;
use App\Actions\MakeUpdateOrder;
use App\Contracts\Cart;
use App\Enums\OrderStatus;
use App\Helpers\Accounting;
use App\Models\Order;
use App\Models\Page;
use App\Registries\PaymentGatewayRegistry;
use App\Settings\MailevaSettings;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use App\Settings\WeightSettings;
use App\Traits\WithPopup;
use App\Traits\WithPreviewFile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class LetterPaymentForm extends Component
{
    use WithPreviewFile;
    use WithPopup;

    public float $weight = 0.0;

    public bool $customerCertifiesHavingReadTheGeneralConditionsOfSale = false;

    public bool $expresslyRequestsTheStartOfTheService = false;

    public string $paymentHandle;

    public ?string $orderNumber = null;

    public ?string $clientSecret = null;

    protected function rules(): array
    {
        return [
            'customerCertifiesHavingReadTheGeneralConditionsOfSale' => 'accepted',
            'expresslyRequestsTheStartOfTheService' => 'accepted'
        ];
    }

    public function mount(): void
    {
        $cart = App::make(Cart::class);

        $weight = App::make(WeightSettings::class)->toArray();

        $this->weight = collect($cart->getDocuments())->sum('number_of_pages') * $weight['paper'][80] + $weight['envelope']['C6'];

        $paymentGateway = App::make(PaymentGatewayRegistry::class)->get(config('hipay.default'));
        $this->paymentHandle = $paymentGateway->getName();

        if($this->paymentHandle === 'stripe') {
            if(Session::has('stripe_session') && Session::has('order_id')) {
                $order = (new MakeUpdateOrder(
                    customer: (new UpdateCustomerAction)(
                        customerData: $cart->getCustomer(),
                    )
                ))->handle(Session::get('order_id'));

                $paymentIndent = $paymentGateway->updatePaymentIndent(
                    id: Session::get('stripe_session'),
                    params: ['amount' => Accounting::addTax($order->amount)]
                );

                $this->clientSecret = $paymentIndent->client_secret;
            } else {
                $order = (new MakeNewOrder(
                    customer: (new CreateCustomerAction)(
                        customerData: $cart->getCustomer()
                    )
                ))->handle();

                Session::put('order_id', $order->id);

                $paymentIndent = $paymentGateway->authorize(
                    order: $order,
                    hasSubscription: $cart->getOrder()->has_subscription,
                );

                $this->clientSecret = $paymentIndent->client_secret;
                Session::put('stripe_session', $paymentIndent->id);
            }
        } else if ($this->paymentHandle === 'hipay') {
            if(Session::has('order_id')) {
                $order = (new MakeUpdateOrder(
                    customer: (new UpdateCustomerAction)(
                        customerData: $cart->getCustomer(),
                    )
                ))->handle(Session::get('order_id'));
            } else {
                $order = (new MakeNewOrder(
                    customer: (new CreateCustomerAction)(
                        customerData: $cart->getCustomer()
                    )
                ))->handle();

                Session::put('order_id', $order->id);
            }

        }

        $this->customerCertifiesHavingReadTheGeneralConditionsOfSale = $order->customer->accept_gcs;
        $this->expresslyRequestsTheStartOfTheService = $order->customer->accept_start_service;
    }

    public function removePromotion(): void
    {
        $cart = App::make(Cart::class);

        $order = $cart->getOrder();

        $amount = 0;

        $weight = App::make(WeightSettings::class)->toArray();

        $pricing = App::make(PricingSettings::class)->toArray();

        $number_of_pages = collect($cart->getDocuments())->sum('number_of_pages');

        $weight = $number_of_pages * $weight['paper'][80] + $weight['envelope']['C6'];

        $number_of_recipients = $cart->getRecipients()->count();

        $amount = $this->postageAmount($cart, $number_of_pages, $weight, $amount, $number_of_recipients);

        if(count($order->options) > 0) {
            $orderOptions = Arr::pluck($order->toArray()['options'], 'name');

            if(in_array('black_print', $orderOptions, true)) {
                $amount += $this->blackPrintAmount($number_of_pages, $pricing) * $number_of_recipients;
            }

            foreach ($orderOptions as $option) {
                if($option === 'color_print') {
                    $amount += $this->colorPrintAmount($number_of_pages, $pricing) * $number_of_recipients;
                } else if($option === 'sms_notification') {
                    $amount += $pricing['sms_notification'] * $number_of_recipients;
                } else if($option === 'receipt') {
                    $amount += $pricing['receipt'] * $number_of_recipients;
                }
            }
        } else {
            $amount += $this->blackPrintAmount($number_of_pages, $pricing) * $number_of_recipients;
        }

        $order->has_subscription = false;
        $order->with_subscription = false;

        $order->amount = $amount;

        $cart->addOrder($order);

        $order = (new MakeUpdateOrder())->handle(Session::get('order_id'));


        if($this->paymentHandle === 'stripe') {
            $paymentGateway = App::make(PaymentGatewayRegistry::class)->get(config('hipay.default'));

            $paymentIndent = $paymentGateway->updatePaymentIndent(
                id: Session::get('stripe_session'),
                params: ['amount' => Accounting::addTax($order->amount)]
            );

            $this->clientSecret = $paymentIndent->client_secret;
        }

        activity()
            ->causedBy($order->customer)
            ->performedOn($order)
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client a renoncé à l\'offre accès+');
    }

    public function updatedShowPopup(bool $value): void
    {
        if($value) {
            $order = Order::find(Session::get('order_id'));

            activity()
                ->causedBy($order->customer)
                ->performedOn($order)
                ->withProperties([
                    'ip' => session()->get('ipClient'),
                    'url' => request()->url(),
                ])
                ->event('onClink')
                ->log('Les conditions générales de vente ont été vues');
        }
    }

    public function save(): string
    {
        $this->validate();

        $cart = App::make(Cart::class);

        $customerData = $cart->getCustomer();
        $customerData->customer_certifies_having_read_the_general_conditions_of_sale = $this->customerCertifiesHavingReadTheGeneralConditionsOfSale;
        $customerData->expressly_requests_the_start_of_the_service = $this->expresslyRequestsTheStartOfTheService;

        $cart->addCustomer($customerData);

        $order = Order::find(Session::get('order_id'));
        $order->customer->accept_gcs = $this->customerCertifiesHavingReadTheGeneralConditionsOfSale;
        $order->customer->accept_start_service = $this->expresslyRequestsTheStartOfTheService;
        $order->customer->save();
        $order->customer->refresh();

        (new CreateBillingAddressAction)(
          addressData: $customerData->billingAddress,
          customer: $order->customer,
        );

        activity()
            ->causedBy($order->customer)
            ->performedOn($order)
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'customer_certifies_having_read_the_general_conditions_of_sale' => $this->customerCertifiesHavingReadTheGeneralConditionsOfSale
            ])
            ->event('onChange')
            ->log('La case a coché "J\'ai lu les conditions générales de vente et j\'y adhère sans réserve" a été validée');

        activity()
            ->causedBy($order->customer)
            ->performedOn($order)
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'expressly_requests_the_start_of_the_service' => $this->expresslyRequestsTheStartOfTheService
            ])
            ->event('onChange')
            ->log('La case a coché "Je demande expressément l\'exécution de ma commande avant la fin du délai de rétraction" a été validée');

        activity()
            ->causedBy($order->customer)
            ->performedOn($order)
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
            ])
            ->event('onClick')
            ->log('La validation des conditions générales de vente et la confirmation du règlement ont été effectuées');

        $this->showPopup = false;

        return OrderStatus::PAID->value;
    }

    public function processPayment(array $hipayResponse): array {
        $cart = App::make(Cart::class);
        $order = Order::find(Session::get('order_id'));

        $paymentGateway = App::make(PaymentGatewayRegistry::class)->get(config('hipay.default'));

        $response = $paymentGateway->progressPayment([
            'order' => $order,
            'has_subscription' => $cart->getOrder()->has_subscription,
            'hipayResponse' => $hipayResponse,
        ]);

        if($response->status === 200 && !$response->url) {
            $this->redirectRoute('frontend.letter.payment.confirmation', ['token', Str::uuid()]);
        } else if($response->status === 200 && $response->url) {
            $this->redirect($response->url);
        }

        return $response->toArray();
    }

    public function render(): View
    {
        $cart = App::make(Cart::class);
        $order = $cart->getOrder()->toArray();

        $subscription = App::make(SubscriptionSettings::class);
        $mailevaSettings = App::make(MailevaSettings::class);

        return view('livewire.letter-payment-form', [
            'cart' => $cart,
            'options' => Arr::pluck($order['options'], 'price', 'name'),
            'has_subscription' => $order['has_subscription'],
            'subscription' => $subscription,
            'amount' => $cart->getOrder()->amount,
            'number_of_pages' => collect($cart->getDocuments())->sum('number_of_pages'),
            'number_of_recipients' => $cart->getRecipients()->count(),
            'page' => Page::where('slug', 'cgv')->first(),
            'mention_maileva' => $mailevaSettings->mention_three,
        ]);
    }

    private function postageAmount(mixed $cart, int $number_of_pages, float $weight, float $amount, int $number_of_recipients): float
    {
        foreach ($cart->getPostageType()->price() as $weightFold => $price) {
            if ($weight <= $weightFold) {
                $amount = $price * $number_of_recipients;
                break;
            }
        }

        return $amount;
    }

    private function blackPrintAmount(int $number_of_pages, array $pricing): float
    {
        $amount = 0;

        for ($page = 1; $page <= $number_of_pages; $page++) {
            if ($page === 1) {
                $amount += $pricing['black_print'][0];
            } else {
                $amount += $pricing['black_print'][1];
            }
        }

        return $amount;
    }

    private function colorPrintAmount(int $number_of_pages, array $pricing): float
    {
        $amount = 0;

        for ($page = 1; $page <= $number_of_pages; $page++) {
            if ($page === 1) {
                $amount += $pricing['color_print'][0];
            } else {
                $amount += $pricing['color_print'][1];
            }
        }

        return $amount;
    }
}
