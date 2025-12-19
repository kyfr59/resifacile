<div class="grid grid-cols-1 gap-3">
    <div class="flex flex-col gap-3">
        <div
            class="text-sm text-white bg-red-500 py-2 px-3 rounded-lg text-center w-full"
            x-show="showError"
            x-ref="error"
            x-cloak
            wire:ignore
        ></div>
        @if($paymentHandle === 'hipay')
            <div wire:ignore class="grid grid-cols-1 gap-3 md:gap-6">
                <div class="w-full champ est-rempli">
                    <label class="text-xs font-medium">Nom sur la carte <em>(porteur)</em></label>
                    <div class="hostedfield relative z-10 h-[40px] px-5 bg-transparent outline-none w-full border-2 rounded-xl border-gray-200" id="hipay-card-holder"></div>
                </div>
                <div class="w-full champ est-rempli">
                    <label class="text-xs font-medium">Numéro de la carte</label>
                    <div class="hostedfield relative z-10 h-[40px] px-5 bg-transparent outline-none w-full border-2 rounded-xl border-gray-200" id="hipay-card-number"></div>
                </div>
                <div class="grid grid-cols-2 gap-3 md:gap-6">
                    <div class="champ est-rempli">
                        <label class="text-xs font-medium">Date de validité</label>
                        <div class="hostedfield relative z-10 h-[40px] px-5 bg-transparent outline-none w-full border-2 rounded-xl border-gray-200" id="hipay-expiry-date"></div>
                    </div>
                    <div class="champ est-rempli">
                        <label class="text-xs font-medium">Cryptogramme <em>(CVC)</em></label>
                        <div class="hostedfield relative z-10 h-[40px] px-5 bg-transparent outline-none w-full border-2 rounded-xl border-gray-200" id="hipay-cvc"></div>
                    </div>
                </div>
                <div class="text-[0.7rem] text-center md:text-left">En fournissant vos informations de carte bancaire, vous autorisez Media Group SAS à débiter votre carte pour les paiements futurs conformément à ses conditions.</div>
            </div>
        @elseif($paymentHandle === 'stripe')
            <div id="payment-element" class="col-span-4" wire:ignore></div>
        @endif
    </div>
    <div
        class="cursor-pointer flex flex-col-reverse md:flex-row justify-between items-center gap-3 md:gap-6"
    >
        <a
            href="{{ route('frontend.letter.validation') }}"
            class="w-full md:w-auto md:flex-1 sm:w-auto hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 text-gray-800 border-2 border-gray-200 h-14 px-3 rounded-xl inline-flex items-center gap-6 justify-center">
            Retour
        </a>
        <button
            x-ref="submit"
            @unless($this->customerCertifiesHavingReadTheGeneralConditionsOfSale)
                x-on:click.prevent="showPopup = true"
            @endunless
            type="submit"
            class="flex-1 w-full sm:w-auto cursor-pointer flex-none bg-blue-700 disabled:bg-gray-500 text-white disabled:cursor-not-allowed py-3 h-14 px-6 rounded-xl flex flex-col items-center justify-center text-xl leading-tight">
            <strong class="relative">Payez @price($amount)<em class="text-sm absolute t-0 inline-block">*</em></strong>
            <div class="text-xs font-italic">Commande avec obligation de paiment</div>
        </button>
    </div>
</div>
