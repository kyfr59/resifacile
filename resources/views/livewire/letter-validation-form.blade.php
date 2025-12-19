@inject('accounting', 'App\Helpers\Accounting')
@php
    $print = 'black_print';

    if(in_array('color_print', $this->options, true)) {
        $print = 'color_print';
    }

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
@endphp
<div class="flex flex-col gap-3 md:gap-6">
    <form wire:submit.prevent="save" class="relative flex flex-col-reverse md:grid md:grid-cols-3 gap-6 md:gap-12 items-start">
        <div class="w-full col-span-1 md:col-span-2 flex flex-col gap-6">
            <div class="bg-white overflow-hidden shadow-md shadow-gray-200/20 sm:border border-gray-200 sm:rounded-[17px] flex flex-col">
                <div id="adobe-dc-view" class="w-full" wire:ignore></div>
            </div>
        </div>
        <div class="w-full md:sticky top-0 md:top-8 flex-col gap-3 flex">
            <div>
                <div class="text-lg font-semibold pb-2"><span class="gradient">Récapitulatif</span> de commande</div>
                <table class="text-xs w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-semibold">Désignation</th>
                            <th class="text-right font-semibold">Qté</th>
                            <th class="text-right font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="leading-tight">Impression 1<sup>ère</sup> page {{ ($print === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $this->options, true) ? 'R°V°' : '' }}</td>
                        <td class="text-right text-sm">{{ $number_of_recipients }}</td>
                        <td class="text-right text-sm">@price($accounting->hasSubscription($pricing[$print][0] * $number_of_recipients))</td>
                    </tr>
                    @if($number_of_pages > 1)
                        <tr>
                            <td class="leading-tight">Impression page suivante {{ ($print === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $this->options, true) ? 'R°V°' : '' }}</td>
                            <td class="text-right text-sm">{{ ($number_of_pages - 1) * $number_of_recipients }}</td>
                            <td class="text-right text-sm">@price($accounting->hasSubscription($pricing[$print][1] * ($number_of_pages - 1) * $number_of_recipients))</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="leading-tight">Affr. {{ $weightFold }}g : {{ Str::lower($cart->getPostageType()->label()) }}</td>
                        <td class="text-right text-sm">{{ $number_of_recipients }}</td>
                        <td class="text-right text-sm">@price($accounting->hasSubscription($priceFold * $number_of_recipients))</td>
                    </tr>
                    @foreach($options as $option)
                        @if($option === 'receipt' || $option === 'sms_notification')
                            <tr>
                                @if($option === 'receipt')
                                    <td class="leading-tight">Justificatif d'accusé réception</td>
                                @elseif($option === 'sms_notification')
                                    <td class="leading-tight">Notification SMS</td>
                                @endif
                                <td class="text-right text-sm">{{ $number_of_recipients }}</td>
                                <td class="text-right text-sm">@price($accounting->hasSubscription($pricing[$option] * $number_of_recipients))</td>
                            </tr>
                        @endif
                    @endforeach
                    @if($has_subscription)
                        <tr>
                            <td colspan="3" class="leading-tight">Service accès+ offert 15 jours puis, @price($subscription->recurring_amount)/mois<sup>*</sup> sans engagement résiliable à tout moment</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <div>
                <div class="bg-gradient-to-tr from-[#fb29cd] to-[#fdc51d] rounded-lg text-white p-3">
                    <table class="text-sm w-full">
                        <tbody>
                        <tr>
                            <td>
                                <div class="text-2xl md:text-xl leading-none font-semibold">Total à règler</div>
                                <div class="text-base md:text-sm leading-none">aujourd'hui</div>
                            </td>
                            <td class="text-right text-4xl font-semibold">@price($accounting->hasSubscription($amount))</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-sm mt-3 text-center">Total de la commande, TVA incluse</div>
            </div>
            <div>
                <label for="customer_certifies_documents_are_compliant" class="bg-white text-base cursor-pointer border-2 rounded-xl p-3 gap-3 flex items-center justify-between border-gray-200 @error('customerCertifiesDocumentsAreCompliant') outline outline-offset-2 outline-4 outline-red-100 @enderror shadow-md shadow-gray-200/20">
                    <div class="flex-auto leading-tight">Je certifie que ma commande est conforme</div>
                    <div class="flex-none w-18 flex justify-end">
                        <div class="flex items-center justify-end">
                            <div class="font-light cursor-pointer inline-flex items-center">
                                <input type="checkbox" id="customer_certifies_documents_are_compliant" wire:model="customerCertifiesDocumentsAreCompliant" value="true" class="toggle-checkbox"/>
                            </div>
                        </div>
                    </div>
                </label>
                @error('customerCertifiesDocumentsAreCompliant')
                    <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-700 text-white h-14 px-3 rounded-xl flex gap-2 items-center justify-center text-lg">
                Je finalise ma commande
            </button>
            <a
                @if($init_postage_selection)
                    href="{{ route('frontend.letter.sender') }}"
                @else
                    href="{{ route('frontend.letter.postage') }}"
                @endif
                class="w-full sm:w-auto hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 mt-3 text-gray-800 border-2 border-gray-200 h-14 px-6 rounded-xl inline-flex items-center gap-6 justify-center">
                Retour
            </a>
        </div>
    </form>
    <div class="bg-white p-6 rounded-xl shadow-xl flex-col gap-3 flex" id="recap">
        <div class="flex md:grid flex-col md:grid-cols-2 gap-6 md:gap-12">
            <div class="text-sm">
                <div class="flex flex-wrap md:gap-3 items-center pb-3">
                    <div class="w-2/3 md:w-1/2 text-sm md:text-xs flex-auto font-semibold">Désignation</div>
                    <div class="hidden md:block w-[60px] text-sm md:text-xs text-right font-semibold">P.U.</div>
                    <div class="hidden md:block w-[60px] text-sm md:text-xs text-right font-semibold">Qté</div>
                    <div class="w-[60px] text-sm md:text-xs text-right font-semibold">Total</div>
                </div>
                <div class="flex flex-wrap gap-3 items-stretch">
                    <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Impression 1<sup>ère</sup> page {{ ($print === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $this->options, true) ? 'R°V°' : '' }}</div>
                    <div class="hidden md:block w-[60px] text-right">@price($accounting->hasSubscription($pricing[$print][0]))</div>
                    <div class="hidden md:block w-[60px] text-right">{{ $number_of_recipients }}</div>
                    <div class="w-[60px] text-right">@price($accounting->hasSubscription($pricing[$print][0] * $number_of_recipients))</div>
                </div>
                @if($number_of_pages > 1)
                    <div class="flex flex-wrap gap-3 items-stretch">
                        <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Impression page suivante {{ ($print === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $this->options, true) ? 'R°V°' : '' }}</div>
                        <div class="hidden md:block w-[60px] text-right">@price($accounting->hasSubscription($pricing[$print][1]))</div>
                        <div class="hidden md:block w-[60px] text-right">{{ ($number_of_pages - 1) * $number_of_recipients }}</div>
                        <div class="w-[60px] text-right">@price($accounting->hasSubscription($pricing[$print][1] * ($number_of_pages - 1) * $number_of_recipients))</div>
                    </div>
                @endif
                <div class="flex flex-wrap gap-3 items-stretch">
                    <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Affr. {{ $weightFold }}g : {{ Str::lower($cart->getPostageType()->label()) }}</div>
                    <div class="hidden md:block w-[60px] text-right">@price($accounting->hasSubscription($priceFold))</div>
                    <div class="hidden md:block w-[60px] text-right">{{ $number_of_recipients }}</div>
                    <div class="w-[60px] text-right">@price($accounting->hasSubscription($priceFold * $number_of_recipients))</div>
                </div>
                @foreach($options as $option)
                    @if($option === 'receipt' || $option === 'sms_notification')
                        <div class="flex flex-wrap gap-3 items-stretch">
                            @if($option === 'receipt')
                                <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Justificatif d'accusé réception</div>
                            @elseif($option === 'sms_notification')
                                <div class="w-2/3 md:w-1/2 leading-tight flex-auto">Notification SMS</div>
                            @endif
                            <div class="hidden md:block w-[60px] text-right">@price($accounting->hasSubscription($pricing[$option]))</div>
                            <div class="hidden md:block w-[60px] text-right">{{ $number_of_recipients }}</div>
                            <div class="w-[60px] text-right">@price($accounting->hasSubscription($pricing[$option] * $number_of_recipients))</div>
                        </div>
                    @endif
                @endforeach
                @if($has_subscription)
                    <div class="flex flex-wrap gap-3 items-stretch">
                        <div class="w-2/3 md:w-1/2 leading-tight flex-auto"><span class="font-semibold sm:font-normal">Offre service accès+ sans engagement</span>, offert 15 jours puis, @price($subscription->recurring_amount)/mois<sup>*</sup></div>
                        <div class="hidden md:block w-[60px] text-right"></div>
                        <div class="hidden md:block w-[60px] text-right"></div>
                        <div class="w-[60px] text-right">@price(0)</div>
                    </div>
                @endif
            </div>
            <div class="bg-gradient-to-tr from-[#fb29cd] to-[#fdc51d] text-white p-6 rounded-[7px] flex gap-3 items-center justify-between">
                <div>
                    <div class="text-xl sm:text-3xl leading-none font-semibold">Total</div>
                    <div class="text-xs sm:text-base leading-none">À règler aujourd'hui</div>
                </div>
                <div class="flex-none text-right text-4xl sm:text-6xl font-semibold">@price($accounting->hasSubscription($amount))<sup class="text-xl sm:text-2xl relative sm:-top-5">*</sup></div>
            </div>
        </div>
    </div>
    <div class="col-span-1 md:col-span-3 grid grid-cols-1">
        @include('components.sections.call')
        @if($has_subscription)
            <div class="text-[.6rem] sm:text-[.7rem] flex flex-col gap-3 text-justify">
                <div><sup>*</sup>{{ $subscription->mention_one }}</div>
            </div>
        @endif
    </div>
</div>
@push('scripts')
    <script src="https://documentservices.adobe.com/view-sdk/viewer.js"></script>
    <script>
        document.addEventListener('adobe_dc_view_sdk.ready', () => {
            const documentViewers = @json($documentViewers);
            const container = document.querySelector('#adobe-dc-view');

            function pdfViewer(index, doc) {
                const div = document.createElement('div');
                div.setAttribute('id', 'preview-' + index)
                container.append(div);

                const adobeDCView = new AdobeDC.View({
                    clientId: '{{ config('adobe.api_viewer') }}',
                    divId: 'preview-' + index
                })

                adobeDCView.previewFile({
                    content: {location: {url: route('frontend.letter.preview', {id: index})}},
                    metaData: {fileName: doc['readable_file_name']}
                }, {
                    embedMode: "IN_LINE",
                    showDownloadPDF: false,
                    showPrintPDF: false,
                });
            }

            if(Array.isArray(documentViewers)) {
                documentViewers.forEach((doc, index) => {
                    pdfViewer(index, doc)
                })
            } else {
                Object.keys(documentViewers).map((key) => pdfViewer(key, documentViewers[key]))
            }
        })
    </script>
@endpush
