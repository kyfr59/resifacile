@inject('accounting', 'App\Helpers\Accounting')

@php
    $priceFold = 0;
    $priceReceipt = 0;
    $weightFold = 0;

    foreach ($cart->getPostageType()->price() as $wFold => $price) {
        if ($weight <= $wFold) {
            $priceFold = $price;
            $weightFold = $wFold;
            break;
        }
    }

    if(array_key_exists('receipt', $options)) {
        $priceReceipt = $options['receipt'];
    }

    $print = 'black_print';
    if(array_key_exists('color_print', $options)) {
        $print = 'color_print';
    }
@endphp

<form
    x-data="hipay({{ $has_subscription ? 'true' : 'false' }}, @entangle('showPopup'))"
    x-init="initHipay"
    id="hipay-form"
    x-on:submit.prevent="$wire.save().then(result => {
        if(result === 'PAID') {
            cardInstance.setMultiUse(hasSubscription);
            cardInstance.getPaymentData().then((response) => {
                $wire.processPayment(response).then(error => {
                    $refs.error.innerHTML = error.message;
                    showWaiting = false;
                    showError = true;
                });
                showWaiting = true;
            }, (errors) => {
                $refs.error.innerHTML = errors[0].error;
                showWaiting = false;
                showError = true;
            });
        }
    })"
>
    @include('components.sale-process.waiting')
    @include('components.sale-process.general-conditions-of-sale')
    <div class="flex flex-col gap-3 md:gap-6">
        <div class="relative flex md:grid flex-col-reverse md:grid-cols-2 gap-6 items-start">
            <div class="relative bg-gradient-to-r from-amber-500 to-pink-500 p-1.5 rounded-xl w-full">
                <div class="relative h-full w-full bg-white rounded-lg p-6 flex flex-col justify-between">
                    <div class="bg-white h-20 w-20 absolute right-0 top-0 -mt-6 -mr-6 shadow-2xl z-10 rounded-full overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('images/laposte.png') }}" width="54px"/>
                    </div>
                    <div class="bg-white">
                        <div class="font-semibold">
                            <div class="inline-block">
                                <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                            </div>
                            <span class="text-gradient inline-block">Jour 1</span>
                        </div>
                        <ul>
                            <li class="font-semibold text-base">
                                <div class="inline-block pl-6">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                </div>
                                Lettre envoyée par email
                            </li>
                            <li class="font-semibold text-base">
                                <div class="inline-block pl-6">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                </div>
                                Lettre envoyée en recommandé <span class="font-normal">par La Poste</span>
                            </li>
                        </ul>
                        <div class="font-semibold text-gradient mt-6">
                            <div class="inline-block">
                                <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                            </div>
                            <span class="text-gradient inline-block">Jour 2</span>
                        </div>
                        <div class="w-full flex justify-center">
                            <img src="{{ asset('images/livraison.png') }}"/>
                        </div>
                        <div class="font-semibold text-gradient mt-6">
                            <div class="inline-block">
                                <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                            </div>
                            <span class="text-gradient inline-block">Jour 3</span>
                        </div>
                        <ul>
                            <li class="font-semibold text-base">
                                <div class="inline-block pl-6">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                </div>
                                Reception de votre lettre <span class="font-normal">par le destinataire</span>
                            </li>
                        </ul>
                        <div class="font-semibold text-gradient mt-6">
                            <div class="inline-block">
                                <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                            </div>
                            <span class="text-gradient inline-block">Jour 4</span>
                        </div>
                        <ul>
                            <li class="font-semibold text-base">
                                <div class="inline-block pl-6">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                </div>
                                Reception de l'accusé <span class="font-normal">de réception</span>
                            </li>
                        </ul>
                        <div class="p-3 mt-6 bg-[#f8f2e8] rounded-md text-sm font-semibold">
                            <ul class="flex flex-col gap-1">
                                <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Distribution J + 3</li>
                                @if($cart->getPostageType() === \App\Enums\PostageType::REGISTERED_LETTER)
                                    <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Preuve de dépôt</li>
                                    @foreach($options as $option => $price)
                                        @if($option === 'receipt')
                                            <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> AR remis par le facteur</li>
                                        @elseif($option === 'sms_notification')
                                            <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Notification SMS</li>
                                        @endif
                                    @endforeach
                                @endif
                                @if($cart->getPostageType() === \App\Enums\PostageType::FOLLOWED_LETTER)
                                    <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-amber-700">✔︎</span> Suivi de votre courrier</li>
                                    @foreach($options as $option => $price)
                                        @if($option === 'sms_notification')
                                            <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Notification SMS</li>
                                        @endif
                                    @endforeach
                                @endif
                                <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Un service support 7J/7</li>
                                <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> -50% sur vos courriers</li>
                                <li><span class="bg-white h-6 w-6 rounded-full inline-flex items-center justify-center text-blue-700">✔︎</span> Plus de 500 modèles</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @include('components.sale-process.sidebar')
        </div>
        {{-- <div class="bg-white md:p-6 sm:rounded-[17px] sm:shadow-md sm:shadow-gray-200/20 sm:border border-gray-200 flex-col gap-3 flex">
            @include('components.sale-process.order')
        </div> --}}
    </div>
    @if($has_subscription)
        <div class="flex pt-3">
            <div class="col-start-2 flex justify-center md:justify-start items-center w-full">
                <div
                    class="cursor-pointer text-gray-400 underline text-xs"
                    wire:click.prevent="removePromotion"
                >
                    Je renonce à l'offre accès+
                </div>
            </div>
        </div>
    @endif
    <div class="col-span-1 md:col-span-3 pt-6 md:pt-8 grid grid-cols-1 gap-6">
        @include('components.sections.call')
        @if($has_subscription)
            <div class="text-[.6rem] sm:text-[.7rem] flex flex-col gap-3 text-justify">
                <div><sup>*</sup>{{ $subscription->mention_one }}</div>
            </div>
        @endif
    </div>
</form>

@pushonce('scripts')
    @if($paymentHandle === 'stripe')
        <script src="https://js.stripe.com/v3/"></script>
    @elseif($paymentHandle === 'hipay')
        <script type="text/javascript" src="https://libs.hipay.com/js/sdkjs.js"></script>
    @endif
@endpushonce
