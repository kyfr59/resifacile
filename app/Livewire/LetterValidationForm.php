<?php

namespace App\Livewire;

use App\Contracts\Cart;
use App\DataTransferObjects\CustomerData;
use App\DataTransferObjects\OptionData;
use App\Enums\DocumentType;
use App\Enums\PostageType;
use App\Enums\SubscriptionStatus;
use App\Helpers\Accounting;
use App\Models\Customer;
use App\Settings\AccountingSettings;
use App\Settings\MailevaSettings;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use App\Settings\WeightSettings;
use App\Traits\WithPreviewFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class LetterValidationForm extends Component
{
    use WithPreviewFile;

    public bool $has_subscription = false;

    public bool $with_subscription = false;

    public float $amount = 0.0;

    public float $weight = 0.0;

    public int $number_of_pages = 0;

    public int $number_of_recipients = 0;

    public array $options = [];

    public array $pricing = [];

    public ?string $phone = null;

    public bool $customerCertifiesDocumentsAreCompliant = false;

    protected $messages = [
        'customerCertifiesDocumentsAreCompliant' => "Vous devez certfier votre commande",
    ];

    private function postageAmount(mixed $cart): void
    {
        foreach ($cart->getPostageType()->price() as $weightFold => $price) {
            if ($this->weight <= $weightFold) {
                $this->amount = $price * $this->number_of_recipients;
                break;
            }
        }
    }

    private function blackPrintAmount(): float
    {
        $amount = 0;

        for ($page = 1; $page <= $this->number_of_pages; $page++) {
            if ($page === 1) {
                $amount += $this->pricing['black_print'][0];
            } else {
                $amount += $this->pricing['black_print'][1];
            }
        }

        return $amount;
    }

    private function colorPrintAmount(): float
    {
        $amount = 0;

        for ($page = 1; $page <= $this->number_of_pages; $page++) {
            if ($page === 1) {
                $amount += $this->pricing['color_print'][0];
            } else {
                $amount += $this->pricing['color_print'][1];
            }
        }

        return $amount;
    }

    protected function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                Rule::requiredIf(fn () => in_array('sms_notification', $this->options, true)),
                'regex:/^0[6-7]\s?[0-9]{2}\s?[0-9]{2}\s?[0-9]{2}\s?[0-9]/i'
            ],
            'customerCertifiesDocumentsAreCompliant' => 'accepted'
        ];
    }

    public function mount(): void
    {
        $cart = App::make(Cart::class);

        $order = $cart->getOrder();

        $weight = App::make(WeightSettings::class)->toArray();

        $this->pricing = App::make(PricingSettings::class)->toArray();

        $this->number_of_pages = collect($cart->getDocuments())->sum('number_of_pages');

        $this->weight = $this->number_of_pages * $weight['paper'][80] + $weight['envelope']['C6'];

        $this->number_of_recipients = $cart->getRecipients()->count();

        $this->postageAmount($cart);

        if(count($order->options) > 0) {
            $orderOptions = Arr::pluck($order->toArray()['options'], 'name');

            if (
                $cart->getPostageType() !== PostageType::REGISTERED_LETTER &&
                in_array('receipt', $orderOptions, true)
            ) {
                array_splice($orderOptions, array_search('receipt', $orderOptions), 1);
            }

            if(in_array('black_print', $orderOptions, true)) {
                unset($orderOptions[array_search('black_print', $orderOptions, true)]);
                $this->amount += $this->blackPrintAmount() * $this->number_of_recipients;
            }

            collect($orderOptions)->each(function ($option) {
                if($option === 'color_print') {
                    $this->amount += $this->colorPrintAmount() * $this->number_of_recipients;
                } else if($option === 'sms_notification') {
                    $this->amount += $this->pricing['sms_notification'] * $this->number_of_recipients;
                } else if($option === 'receipt') {
                    $this->amount += $this->pricing['receipt'] * $this->number_of_recipients;
                }
            });

            $this->options = Arr::collapse([$this->options, $orderOptions]);
        } else if ($cart->getPostageType() === PostageType::REGISTERED_LETTER) {
            $this->options[] = 'receipt';
            $this->amount += $this->pricing['receipt'] * $this->number_of_recipients;
            $this->amount += $this->blackPrintAmount() * $this->number_of_recipients;
        } else {
            $this->amount += $this->blackPrintAmount() * $this->number_of_recipients;
        }

        $customer = $cart->getCustomer();
        if($customer->phone) {
            $this->phone = 0 . substr(str_replace(' ', '', $customer->phone), 3);
        }

        $this->customerCertifiesDocumentsAreCompliant = $order->customer_certifies_documents_are_compliant;

        $customerModel = Customer::where('email',$customer->email)->first();

        if(
            $customerModel?->subscription &&
            (
                $customerModel->subscription->status === SubscriptionStatus::TRIAL ||
                $customerModel->subscription->status === SubscriptionStatus::RECURRING
            )
        ) {
            $order->has_subscription = false;
            $cart->addOrder($order);

            $this->has_subscription = false;
            $this->with_subscription = true;
        } else {
            $this->has_subscription = $order->has_subscription;
            $this->with_subscription = $order->with_subscription;
        }
    }

    public function updatedOptions(array $options): void
    {
        $cart = App::make(Cart::class);
        $this->postageAmount($cart);

        if(!empty($options)) {
            foreach ($options as $option) {
                if($option === 'color_print') {
                    $this->amount += $this->colorPrintAmount() * $this->number_of_recipients;
                } else {
                    $this->amount += $this->pricing[$option] * $this->number_of_recipients;
                }
            }

            if(!in_array('color_print', $this->options, true) && !in_array('black_print', $this->options, true)) {
                $this->amount += $this->blackPrintAmount() * $this->number_of_recipients;
            }
        } else {
            $this->amount += $this->blackPrintAmount() * $this->number_of_recipients;
        }
    }

    public function removePromotion()
    {
        $this->has_subscription = false;
        $order = App::make(Cart::class)->getOrder();
        $order->has_subscription = $this->has_subscription;
        $order->with_subscription = $this->has_subscription;
        App::make(Cart::class)->addOrder($order);

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client a renoncé à l\'offre accès+');
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        $cart = App::make(Cart::class);
        $accountingSettings = App::make(AccountingSettings::class);

        $options = [];

        foreach ($this->options as $option) {
            $options[] = new OptionData(
                name: $option,
                price: $this->pricing[$option],
            );
        }

        if(!in_array('color_print', $this->options, true)) {
            $options[] = new OptionData(
                name: 'black_print',
                price: $this->pricing['black_print'],
            );
        }

        $order = $cart->getOrder();

        $order->options = OptionData::collection($options);
        $order->vat_rate = $accountingSettings->vat_rate;
        $order->amount = Accounting::hasSubscription($this->amount);
        $order->customer_certifies_documents_are_compliant = $this->customerCertifiesDocumentsAreCompliant;
        $order->has_subscription = $this->has_subscription;
        $order->with_subscription = $this->with_subscription;
        $cart->addOrder($order);

        if($this->phone && in_array('sms_notification', $this->options, true)) {
            $customer = $cart->getCustomer();

            if($this->phone) {
                $customer->phone = '+33' . substr(str_replace(' ', '', $this->phone), 1);
            }

            $cart->addCustomer($customer);
        }

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
                'customer_certifies_documents_are_compliant' => $this->customerCertifiesDocumentsAreCompliant
            ])
            ->event('onClick')
            ->log('Le client à certifier conforme son courrier');

        return redirect()->route('frontend.letter.payment');
    }

    public function render(): View
    {
        $cart = App::make(Cart::class);
        $accountingSettings = App::make(AccountingSettings::class);
        $mailevaSettings = App::make(MailevaSettings::class);

        $subscription = App::make(SubscriptionSettings::class);

        return view('livewire.letter-validation-form', [
            'cart' => $cart,
            'rat_vat' => $accountingSettings->vat_rate,
            'subscription' => $subscription,
            'documentViewers' => collect($cart->getDocuments()),
            'mention_maileva' => $mailevaSettings->mention_three,
            'init_postage_selection' => $cart->getOrder()->init_postage_selection,
        ]);
    }
}
