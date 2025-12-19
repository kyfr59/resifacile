<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use App\DataTransferObjects\TransactionRedirectData;
use App\Enums\OrderStatus;
use App\Helpers\Accounting;
use App\Models\Customer as ECCustomer;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction as ECTransaction;
use App\Settings\AccountingSettings;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use Exception;
use HiPay\Fullservice\Enum\ThreeDSTwo\DeliveryTimeFrame;
use HiPay\Fullservice\Enum\ThreeDSTwo\DeviceChannel;
use HiPay\Fullservice\Enum\ThreeDSTwo\NameIndicator;
use HiPay\Fullservice\Enum\ThreeDSTwo\PurchaseIndicator;
use HiPay\Fullservice\Enum\ThreeDSTwo\ReorderIndicator;
use HiPay\Fullservice\Enum\ThreeDSTwo\ShippingIndicator;
use HiPay\Fullservice\Enum\Transaction\TransactionState;
use HiPay\Fullservice\Gateway\Client\GatewayClient;
use HiPay\Fullservice\Gateway\Model\Cart\Cart;
use HiPay\Fullservice\Gateway\Model\Cart\Item;
use HiPay\Fullservice\Gateway\Model\Operation;
use HiPay\Fullservice\Gateway\Model\Request\ThreeDSTwo\AccountInfo;
use HiPay\Fullservice\Gateway\Model\Request\ThreeDSTwo\BrowserInfo;
use HiPay\Fullservice\Gateway\Model\Request\ThreeDSTwo\MerchantRiskStatement;
use HiPay\Fullservice\Gateway\Model\Request\ThreeDSTwo\PreviousAuthInfo;
use HiPay\Fullservice\Gateway\Model\Request\ThreeDSTwo\RecurringInfo;
use HiPay\Fullservice\Gateway\Model\Transaction;
use HiPay\Fullservice\Gateway\Request\Info\CustomerBillingInfoRequest;
use HiPay\Fullservice\Gateway\Request\Order\OrderRequest;
use HiPay\Fullservice\Gateway\Request\PaymentMethod\CardTokenPaymentMethod;
use HiPay\Fullservice\HTTP\Configuration\Configuration;
use HiPay\Fullservice\HTTP\SimpleHTTPClient;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class HipayDriver implements PaymentGateway
{
    private GatewayClient $gatewayClient;
    private int $eci;

    private const TRANSACTION = 7;
    private const RECURRING_TRANSACTION = 9;
    private const CURRENCY = 'EUR';
    private const REFUND = 'refund';

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly string $user,
        private readonly string $password,
        private readonly string $env,
    )
    {
        $this->gatewayClient = new GatewayClient(
            new SimpleHTTPClient(
                new Configuration([
                    "apiUsername" => $this->user,
                    "apiPassword" => $this->password,
                    "apiEnv" => $this->env === Configuration::API_ENV_PRODUCTION ? Configuration::API_ENV_PRODUCTION : Configuration::API_ENV_STAGE,
                ])
            )
        );

        $this->eci = self::TRANSACTION;
    }

    public function getName(): string
    {
        return 'hipay';
    }

    public function capture(array $payload): void
    {
        $this->gatewayClient->requestMaintenanceOperation(
            $payload['operation'],
            $payload['transaction_id'],
            $payload['amount'],
            $payload['operation_id'],
        );
    }

    public function progressPayment(array $payload): TransactionRedirectData|bool
    {
        $transaction = $this->gatewayClient->requestNewOrder(
            $this->orderRequest($payload)
        );

        $this->transactionLogging($payload['order'], $transaction);

        return $this->transactionRedirect($transaction);
    }

    public function progressSubscriptionPayment(Subscription $payload): void
    {
        $order = Order::where('id', $payload->meta_data->order_id)->first();

        $accountingSettings = App::make(AccountingSettings::class);

        $vat_rate = $accountingSettings->vat_rate;

        $paymentMethod = $payload->customer->paymentMethods()->first();

        $paymentInfo = new AccountInfo\Payment();
        $paymentInfo->enrollment_date = (int)$payload->created_at->format('Ymd');

        $accountInfo = new AccountInfo();

        $accountInfo->customer = $this->customerInfo($payload->customer);
        $accountInfo->purchase = $this->purchaseInfo($payload->customer);
        $accountInfo->payment = $paymentInfo;

        $orderRequest = new OrderRequest();

        $orderRequest->orderid = 'SUB-' . $payload->id . '-' . now()->format('ym');
        $orderRequest->operation = 'Sale';
        $orderRequest->payment_product = $paymentMethod->data->payment_product;
        $orderRequest->description = 'Abonnement service accès+ échéance du ' . now()->format('d/m/Y');
        $orderRequest->long_description = 'Renouvellement abonnement service accès+ ' .
            Accounting::addTax($payload->price) / 100 .
            '€ par mois; Souscrit le ' .
            $payload->created_at->format('d/m/Y');
        $orderRequest->currency = self::CURRENCY;
        $orderRequest->amount = Accounting::addTax($payload->price) / 100;
        $orderRequest->shipping = 0.0;
        $orderRequest->tax = $payload->price * $vat_rate / 10000;
        $orderRequest->cid = 'USC' . $payload->customer->id;
        $orderRequest->ipaddr = '0.0.0.0';
        $orderRequest->notify_url = route('webhook-client-hipay');
        //$orderRequest->device_fingerprint = $payload['device_fingerprint'];
        $orderRequest->customerBillingInfo = $this->customerBillingInfo($payload->customer);
        $orderRequest->custom_data = [
            'subscription_id' => $payload->id,
            'customer_id' => $payload->customer_id,
            'is_subscription_transaction' => 1,
        ];
        //$orderRequest->recurring_info = $this->recurringInfo($payload);
        $orderRequest->account_info = $accountInfo;
        $orderRequest->previous_auth_info = $this->previousAuthInfo($payload->customer);
        $orderRequest->paymentMethod = $this->cardTokenPaymentMethod($paymentMethod->data->token);

        $this->gatewayClient->requestNewOrder(
            $orderRequest
        );
    }

    public function setSubscription(bool $isSubscription = false): void
    {
        $this->eci = $isSubscription ? self::RECURRING_TRANSACTION : self::TRANSACTION;
    }

    private function isSubscription(): bool
    {
        return $this->eci === self::RECURRING_TRANSACTION;
    }

    private function cardTokenPaymentMethod(string $cardToken): CardTokenPaymentMethod
    {
        $paymentMethod = new CardTokenPaymentMethod();
        $paymentMethod->cardtoken = $cardToken;
        $paymentMethod->eci = $this->eci;
        $paymentMethod->authentication_indicator = ($this->eci === self::TRANSACTION)? 1 : 0;

        return $paymentMethod;
    }

    private function orderRequest(array $payload): OrderRequest
    {
        $recurring_amount = App::make(SubscriptionSettings::class)->recurring_amount;

        $orderRequest = new OrderRequest();
        $orderRequest->orderid = $payload['order']->number;
        $orderRequest->operation = 'Sale';
        $orderRequest->payment_product = $payload['hipayResponse']['payment_product'];
        $orderRequest->description = ($payload['has_subscription']) ?
            'Envoi courrier avec affranchissement ' . Str::lower($payload['order']->postage->label()) . ' et sous souscription abonnement service accès+ ' . Accounting::addTax($recurring_amount) . '€ par mois' :
            'Envoi courrier avec affranchissement ' . Str::lower($payload['order']->postage->label());
        $orderRequest->currency = self::CURRENCY;
        $orderRequest->amount = Accounting::addTax($payload['order']->amount) / 100;
        $orderRequest->shipping = 0.0;
        $orderRequest->tax = $payload['order']->amount * $payload['order']->vat_rate / 10000;
        $orderRequest->cid = 'USC' . $payload['order']->customer->id;
        $orderRequest->ipaddr = $payload['order']->ip_address;
        $orderRequest->accept_url = route('frontend.letter.payment.confirmation', ['token' => Str::uuid()]);
        $orderRequest->decline_url = route('frontend.letter.payment');
        $orderRequest->pending_url = route('frontend.letter.payment.confirmation', ['token' => Str::uuid()]);
        $orderRequest->exception_url = route('frontend.letter.payment');
        $orderRequest->cancel_url = route('frontend.letter.payment');
        $orderRequest->notify_url = route('webhook-client-hipay');
        $orderRequest->http_accept = $_SERVER['HTTP_ACCEPT'] ?? null;
        $orderRequest->http_user_agent = $payload['hipayResponse']['browser_info']['http_user_agent'];
        if(array_key_exists('device_fingerprint', $payload['hipayResponse'])) {
            $orderRequest->device_fingerprint = $payload['hipayResponse']['device_fingerprint'];
        }
        $orderRequest->language =  $payload['hipayResponse']['browser_info']['language'];
        $orderRequest->customerBillingInfo = $this->customerBillingInfo($payload['order']->customer);
        $orderRequest->custom_data = [
            'order_id' => $payload['order']->id,
            'customer_id' => $payload['order']->customer_id,
            'has_subscription' => (int)$payload['has_subscription'],
            'is_subscription_transaction' => 0,
        ];
        $orderRequest->device_channel = DeviceChannel::BROWSER;
        //$orderRequest->recurring_info = $this->recurringInfo($payload['order']->customer->subscription);
        $orderRequest->browser_info = $this->browserInfo($payload['hipayResponse']['browser_info'], $payload['order']->ip_address);
        $orderRequest->account_info = $this->accountInfo($payload['order']);
        $orderRequest->previous_auth_info = $this->previousAuthInfo($payload['order']->customer);
        $orderRequest->merchant_risk_statement = $this->merchantRiskStatement($payload['order']->customer);
        $orderRequest->paymentMethod = $this->cardTokenPaymentMethod($payload['hipayResponse']['token']);

        return $orderRequest;
    }

    //private function cartInfo(Order $order)
    //{
    //    $cartInfo = new Cart();
    //    $item = new Item();
    //    $item->setType('good');
    //    $item->setName('Envoi courrier avec affranchissement ' . Str::lower($payload['order']->postage->label()));
    //    $item->setQuantity('1');
    //    $item->setUnitPrice($payload['order']->amount / 2);
    //    $item->setTaxRate();
    //    $item->setTotalAmount();
    //}

    private function accountInfo(Order $order): AccountInfo
    {
        $accountInfo = new AccountInfo();
        $accountInfo->customer = $this->customerInfo($order->customer);
        $accountInfo->purchase = $this->purchaseInfo($order->customer);
        $accountInfo->payment = $this->paymentInfo($order);
        $accountInfo->shipping = $this->shippingInfo();

        return $accountInfo;
    }

    private function customerInfo(ECCustomer $customer): AccountInfo\Customer
    {
        $customerInfo = new AccountInfo\Customer();
        $customerInfo->account_change = (int)$customer->updated_at->format('Ymd');
        $customerInfo->opening_account_date = (int)$customer->created_at->format('Ymd');

        return $customerInfo;
    }

    private function purchaseInfo(ECCustomer $customer): AccountInfo\Purchase
    {
        $purchaseInfo = new AccountInfo\Purchase();
        $purchaseInfo->count = $customer->orders()
            ->where('status', OrderStatus::PAID->value)
            ->where('created_at', '>=', now()->subMonth(6))
            ->count();
        //$purchaseInfo->card_stored_24h = 1;
        //$purchaseInfo->payment_attempts_24h = 1;
        //$purchaseInfo->payment_attempts_1y = 1;

        return $purchaseInfo;
    }

    private function paymentInfo(Order $order): AccountInfo\Payment
    {
        $enrollment_date = null;

        if($order->customer->subscription) {
            $enrollment_date = (int)$order->customer->subscription->created_at->format('Ymd');
        } else if ($order->with_subscription) {
            $enrollment_date = (int)now()->format('Ymd');
        }

        $paymentInfo = new AccountInfo\Payment();
        $paymentInfo->enrollment_date = $enrollment_date;

        return $paymentInfo;
    }

    private function customerBillingInfo(ECCustomer $customer): CustomerBillingInfoRequest
    {
        $address = $customer->addresses()
            ->where('is_billing_address', true)
            ->first();

        if(! $address) {
            $address = $customer->addresses()
                ->orderByDesc('id')
                ->first();
        }

        $customerBillingInfo = new CustomerBillingInfoRequest();
        $customerBillingInfo->email = $customer->email;
        $customerBillingInfo->phone = Str::replace('+33', '0', $customer->phone);
        $customerBillingInfo->msisdn = $customer->phone;
        $customerBillingInfo->birthdate = '';
        $customerBillingInfo->gender = '';
        $customerBillingInfo->firstname = $customer->first_name;
        $customerBillingInfo->lastname = $customer->last_name;
        $customerBillingInfo->recipientinfo = '';
        $customerBillingInfo->streetaddress = $address->address_line_4;
        $customerBillingInfo->streetaddress2 = ($address->address_line_3 != $address->address_line_4) ? $address->address_line_3 : '';
        $customerBillingInfo->city = $address->city;
        $customerBillingInfo->state = '';
        $customerBillingInfo->zipcode = $address->postal_code;
        $customerBillingInfo->country = $address->country_code;

        return $customerBillingInfo;
    }

    //private function recurringInfo(Subscription|null $subscription): RecurringInfo
    //{
    //    $recurringInfo = new RecurringInfo();
    //    $recurringInfo->expiration_date = (int)($subscription?->current_period_end_at->addDays(30)->format('Ymd') ?? now()->addDays(2)->format('Ymd'));
    //    $recurringInfo->frequency = 30;
    //    return $recurringInfo;
    //}

    private function shippingInfo(): AccountInfo\Shipping
    {
        $shippingInfo = new AccountInfo\Shipping();
        $shippingInfo->name_indicator = NameIndicator::IDENTICAL;

        return $shippingInfo;
    }

    private function browserInfo(array $browserInfoData, string $adresseIp): BrowserInfo
    {
        $browserInfo = new BrowserInfo();
        $browserInfo->ipaddr = $adresseIp;
        $browserInfo->http_accept = $_SERVER['HTTP_ACCEPT'] ?? null;
        $browserInfo->javascript_enabled = $browserInfoData['javascript_enabled'];
        $browserInfo->java_enabled = $browserInfoData['java_enabled'];
        $browserInfo->language = $browserInfoData['language'];
        $browserInfo->color_depth = (int)$browserInfoData['color_depth'];
        $browserInfo->screen_height = (int)$browserInfoData['screen_height'];
        $browserInfo->screen_width = (int)$browserInfoData['screen_width'];
        $browserInfo->timezone = $browserInfoData['timezone'];
        $browserInfo->http_user_agent = $browserInfoData['http_user_agent'];

        return $browserInfo;
    }

    private function previousAuthInfo(ECCustomer $customer): PreviousAuthInfo
    {
        $ids = $customer->orders->pluck('id')->toArray();
        if($customer->subscription) {
            $ids[] = $customer->subscription->id;
        }

        $transaction = ECTransaction::whereIn('transactionable_id', $ids)
            ->orderBy('id', 'desc')
            ->first();

        $previousAuthInfo = new PreviousAuthInfo();
        $previousAuthInfo->transaction_reference = ($transaction) ? $transaction->transaction_id : '';

        return $previousAuthInfo;
    }

    private function merchantRiskStatement(ECCustomer $customer): MerchantRiskStatement
    {
        $merchantRiskStatement = new MerchantRiskStatement();
        $merchantRiskStatement->email_delivery_address = $customer->email;
        $merchantRiskStatement->delivery_time_frame = DeliveryTimeFrame::ELECTRONIC_DELIVERY;
        $merchantRiskStatement->purchase_indicator = PurchaseIndicator::MERCHANDISE_AVAILABLE;
        $merchantRiskStatement->reorder_indicator = ReorderIndicator::FIRST_TIME_ORDERED;
        $merchantRiskStatement->shipping_indicator = ShippingIndicator::DIGITAL_GOODS;

        return $merchantRiskStatement;
    }

    private function transactionRedirect(Transaction $transaction): transactionRedirectData
    {
        $forwardUrl = $transaction->getForwardUrl();

        switch($transaction->getState()) {
            case TransactionState::COMPLETED:
            case TransactionState::PENDING:
                $redirect = new TransactionRedirectData(
                    status: 200,
                );
                break;
            case TransactionState::FORWARDING:
                $redirect = new TransactionRedirectData(
                    status: 200,
                    url: $forwardUrl
                );
                break;
            case TransactionState::DECLINED:
            case TransactionState::ERROR:
                $reason = $transaction->getReason();

                $redirect = new TransactionRedirectData(
                    status: 500,
                    message: "Une erreur s'est produite, le processus a été annulé. Veuillez essayer une autre carte."
                );
                break;
            default:
                $redirect = new TransactionRedirectData(
                    status: 500,
                    message: "Une erreur s'est produite, le processus a été annulé. Veuillez essayer une autre carte."
                );
        }

        return $redirect;
    }

    private function transactionLogging(Order $order, Transaction $transaction): void
    {
        switch($transaction->getState()) {
            case TransactionState::COMPLETED:
            case TransactionState::PENDING:
                activity()
                    ->causedBy($order->customer)
                    ->performedOn($order)
                    ->withProperties([
                        'ip' => session()->get('ipClient'),
                        'url' => request()->url()
                    ])
                    ->event('hipayTransactionResponse')
                    ->log("La commande a été réglé");
                    break;
            case TransactionState::FORWARDING:
                activity()
                    ->causedBy($order->customer)
                    ->performedOn($order)
                    ->withProperties([
                        'ip' => session()->get('ipClient'),
                        'url' => request()->url()
                    ])
                    ->event('hipayTransactionResponse')
                    ->log("Le client a été redirigé vers l'authentification 3D Secure");
                break;
            case TransactionState::DECLINED:
            case TransactionState::ERROR:
                activity()
                ->causedBy($order->customer)
                    ->performedOn($order)
                    ->withProperties([
                        'ip' => session()->get('ipClient'),
                        'url' => request()->url()
                    ])
                    ->event('hipayTransactionResponse')
                    ->log("Une erreur s'est produite, le processus a été annulé");
                break;
            default:
        }
    }

    public function refund(
        string $transaction_reference,
        float|null $amount,
        int $operation_id,
    ): Operation|string
    {
        try {
            return $this->gatewayClient->requestMaintenanceOperation(
                self::REFUND,
                $transaction_reference,
                $amount ?? null,
                $operation_id,
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
