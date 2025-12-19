<div class="flex flex-col gap-6">
    <div class="text-sm">
        <div>
            <div class="flex flex-wrap md:gap-3 items-center pb-3">
                <div class="w-2/3 md:w-1/2 text-sm md:text-xs flex-auto font-semibold">Désignation</div>
                <div class="hidden md:block w-[60px] text-sm md:text-xs text-right font-semibold">Qté</div>
                <div class="w-[60px] text-sm md:text-xs text-right font-semibold">Total</div>
            </div>
            <div class="flex flex-wrap gap-3 items-stretch">
                <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Impression 1<sup>ère</sup> page {{ ($print === 'color_print')?'couleur':'N/B' }} {{ array_key_exists('recto_verso', $options) ? 'R°V°' : '' }}</div>
                <div class="hidden md:block w-[60px] text-right">{{ $cart->getRecipients()->count() }}</div>
                <div class="w-[60px] text-right">@price($accounting->hasSubscription($options[$print][0] * $cart->getRecipients()->count()))</div>
            </div>
            @if(collect($cart->getDocuments())->sum('number_of_pages') > 1)
                <div class="flex flex-wrap gap-3 items-stretch">
                    <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Impression page suivante {{ ($print === 'color_print')?'couleur':'N/B' }} {{ array_key_exists('recto_verso', $options) ? 'R°V°' : '' }}</div>
                    <div class="hidden md:block w-[60px] text-right">{{ (collect($cart->getDocuments())->sum('number_of_pages') - 1) * $cart->getRecipients()->count() }}</div>
                    <div class="w-[60px] text-right">@price($accounting->hasSubscription($options[$print][1] * (collect($cart->getDocuments())->sum('number_of_pages') - 1) * $cart->getRecipients()->count()))</div>
                </div>
            @endif
            <div class="flex flex-wrap gap-3 items-stretch">
                <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Affr. {{ $weightFold }}g : {{ Str::lower($cart->getPostageType()->label()) }}</div>
                <div class="hidden md:block w-[60px] text-right">{{ $cart->getRecipients()->count() }}</div>
                <div class="w-[60px] text-right">@price($accounting->hasSubscription($priceFold * $cart->getRecipients()->count()))</div>
            </div>
            @foreach($options as $option => $price)
                @if($option === 'receipt')
                    <div class="flex flex-wrap gap-3 items-stretch">
                        <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Justificatif d'accusé réception</div>
                        <div class="hidden md:block w-[60px] text-right">{{ $cart->getRecipients()->count() }}</div>
                        <div class="w-[60px] text-right">@price($accounting->hasSubscription($priceReceipt * $cart->getRecipients()->count()))</div>
                    </div>
                @elseif($option === 'sms_notification')
                    <div class="flex flex-wrap gap-3 items-stretch">
                        <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Notification SMS</div>
                        <div class="hidden md:block w-[60px] text-right">{{ $cart->getRecipients()->count() }}</div>
                        <div class="w-[60px] text-right">@price($accounting->hasSubscription($price * $cart->getRecipients()->count()))</div>
                    </div>
                @endif
            @endforeach
            @if($has_subscription)
                <div class="flex flex-wrap gap-3 items-stretch">
                    <div class="w-2/3 md:w-1/2 leading-tight flex-auto"><span class="font-semibold sm:font-normal">Offre accès+ sans engagement</span>, offert 15 jours puis, @price($subscription->recurring_amount)/mois<sup>*</sup></div>
                    <div class="hidden md:block w-[60px] text-right"></div>
                    <div class="w-[60px] text-right">@price(0)</div>
                </div>
            @endif
        </div>
    </div>
    <div class="bg-[#f8f2e8] p-6 rounded-[7px] flex gap-3 items-center justify-between">
        <div>
            <div class="text-xl sm:text-3xl leading-none font-semibold text-[#e07562]">Total</div>
            <div class="text-xs sm:text-base leading-none">À règler aujourd'hui</div>
        </div>
        <div class="flex-none text-right text-4xl sm:text-6xl font-semibold">@price($amount)<sup class="text-xl sm:text-2xl relative sm:-top-5">*</sup></div>
    </div>
</div>
