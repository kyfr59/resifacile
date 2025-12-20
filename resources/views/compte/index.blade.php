<x-layouts.app>
    <x-slot:head>
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="Envoyez vos courriers sans vous déplacer ! — {{ config('app.name') }}"/>
        <?php /* <meta name="robots" content="noindex, noarchive, nocache, noimageindex" /> */ ?>
        <meta name="robots" content="noindex, nofollow, noarchive, noimageindex">
    </x-slot:head>
    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <article
                class="w-full max-w-7xl mx-auto p-6 md:p-12 md:p-16"
                x-data="{tab:'mes-cordonnees'}"
            >
                <div class="pb-6">
                    @include('components.sections.tracking')
                </div>
                <div><h1>Mon <strong class="gradient">compte</strong></h1></div>
                <div class="grid grid-cols-1 gap-6 md:gap-0 md:grid-cols-8 bg-white sm:rounded-[14px] sm:border-gray-200 sm:border sm:shadow-md sm:shadow-gray-200/20">
                    <div class="flex md:col-span-2 sm:p-6 flex-col sm:flex-row md:flex-col gap-1 sm:border-b md:border-b-0 md:border-r border-gray-200 items-stretch justify-start">
                        <div
                            class="cursor-pointer px-3 py-1.5 hover:bg-amber-50 flex items-center rounded-[7px] w-full"
                            x-bind:class="tab === 'mes-cordonnees' ? 'bg-gradient-to-r from-pink-500 to-amber-500 text-white':'text-blue-700'"
                            x-on:click.prevent="tab = 'mes-cordonnees'"
                        >
                            <span class="flex-auto">Mes coordonnées</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="flex-none h-3 stroke-current"><path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M3.85.5 10 6.65a.48.48 0 0 1 0 .7L3.85 13.5"/></svg>
                        </div>
                        <div
                            class="cursor-pointer rounded-[7px] px-3 py-1.5 hover:bg-amber-50 flex items-center w-full"
                            x-bind:class="tab === 'mes-courriers' ? 'bg-gradient-to-r from-pink-500 to-amber-500 text-white':'text-blue-700'"
                            x-on:click.prevent="tab = 'mes-courriers'"
                        >
                            <span class="flex-auto">Mes commandes</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="flex-none h-3 stroke-current"><path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M3.85.5 10 6.65a.48.48 0 0 1 0 .7L3.85 13.5"/></svg>
                        </div>
                        {{--
                        @if(auth()->guard('site')->user()->subscription)
                            <div
                                class="cursor-pointer rounded-[7px] px-3 py-1.5 hover:bg-amber-50 flex items-center w-full"
                                x-bind:class="tab === 'mon-abonnement' ? 'bg-gradient-to-r from-pink-500 to-amber-500 text-white':'text-blue-700'"
                                x-on:click.prevent="tab = 'mon-abonnement'"
                            >
                                <span class="flex-auto">Mon abonnement</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="flex-none h-3 stroke-current"><path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M3.85.5 10 6.65a.48.48 0 0 1 0 .7L3.85 13.5"/></svg>
                            </div>
                        @endif
                        --}}
                    </div>
                    <div class="col-span-1 md:col-span-6 md:my-6 md:px-6">
                        <div class="grid grid-cols-1">
                            <div x-show="tab === 'mes-cordonnees'" x-cloak class="flex flex-col gap-y-6">
                                <livewire:change-profile-information-from/>
                                <livewire:delete-account-from/>
                            </div>
                            <div x-show="tab === 'mes-courriers'" x-cloak>
                                @unless(auth()->guard('site')->user()->orders)
                                @else
                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach(auth()->guard('site')->user()->orders()->orderBy('number')->get() as $order)
                                            @php
                                                $number_of_recipients = collect($order->details->recipients)->count();
                                                $number_of_pages = collect($order->details->documents)->sum('number_of_pages');
                                                $weight = App::make(\App\Settings\WeightSettings::class);
                                                $weight = $number_of_pages * $weight->paper[80] + $weight->envelope['C6'];
                                                $priceFold = 0;
                                                $priceReceipt = 0;
                                                $weightFold = 0;
                                                foreach ($order->details->pricing_postage as $wFold => $price) {
                                                    if ($weight <= $wFold) {
                                                        $priceFold = $price;
                                                        $weightFold = $wFold;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <div class="border border-gray-200 py-3 shadow-md shadow-gray-200/20 text-sm" x-data="{
                                                show: false,
                                                hidden: false,
                                                previewUrl(previewUrl, previewName) {
                                                    const adobeDCView = new AdobeDC.View({
                                                        clientId: '{{ config('adobe.api_viewer') }}',
                                                        divId: 'adobe-dc-view'
                                                    })

                                                    adobeDCView.previewFile({
                                                        content: {location: {url: previewUrl }},
                                                        metaData: {fileName: previewName }
                                                    }, {
                                                        embedMode: 'IN_LINE',
                                                        showDownloadPDF: false,
                                                        showPrintPDF: false,
                                                    })

                                                    this.hidden = true
                                                }
                                            }">
                                                <div class="fixed z-50 inset-0 p-6 flex justify-center items-start bg-blue-700 bg-opacity-75" x-show="hidden" x-cloak>
                                                    <div class="bg-[#7f7f7f] shadow-xl w-full max-w-4xl relative overflow-hidden rounded-md">
                                                        <button class="w-6 h-6 absolute top-4 right-4 cursor-pointer"
                                                                x-on:click.prevent="hidden = false"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="w-6 h-6 text-blue-700">
                                                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="m13.5.5-13 13M.5.5l13 13"/>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                        <div id="adobe-dc-view"></div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col sm:flex-row sm:gap-3 px-3 cursor-pointer divide-y divide-gray-200 sm:divide-y-0" x-on:click.prevent="show = !show">
                                                    <div class="py-2 sm:py-0 flex-none w-24">{!! $order->status->asHtml() !!}</div>
                                                    <div class="py-2 sm:py-0 flex-auto">{{ $order->number }} : {!! $order->postage->label() !!}</div>
                                                    <div class="py-2 sm:py-0 flex-none sm:text-right">@price($order->amount)</div>
                                                    <div class="py-2 sm:py-0 flex-none">{{ $order->created_at->format('d/m/Y') }}</div>
                                                </div>
                                                <div class="p-6" x-show="show" x-cloak>
                                                    <div class="pb-6">
                                                        <div class="text-sm font-semibold pb-2">Ma facture</div>
                                                        <div class="grid grid-cols-1 gap-1.5">
                                                            @foreach($order->transactions()->where('status', \App\Enums\TransactionStatus::CAPTURED->value)->whereNot('transactionable_type', 'App\\Models\\Subscription')->get() as $index => $transaction)
                                                                <div
                                                                    class="flex items-center bg-[#64605e] text-white rounded-[7px] h-[40px] gap-2 px-4 text-sm shadow-document"
                                                                    x-on:click.prevent="previewUrl('{{ route('frontend.account.preview.invoice', ['id' => $transaction->invoice->id]) }}', '{{ $transaction->invoice->number }}')"
                                                                >
                                                                    <div class="flex-auto text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $transaction->invoice->number }}</div>
                                                                    <div class="pl-2 flex items-center justify-end">
                                                            <span class="cursor-pointer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14" class="h-4 stroke-current">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.23 6.246c.166.207.258.476.258.754 0 .279-.092.547-.258.754C12.18 9.025 9.79 11.5 7 11.5c-2.79 0-5.18-2.475-6.23-3.746A1.208 1.208 0 0 1 .512 7c0-.278.092-.547.258-.754C1.82 4.975 4.21 2.5 7 2.5c2.79 0 5.18 2.475 6.23 3.746Z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                                </svg>
                                                            </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="pb-6">
                                                        <div class="text-sm font-semibold pb-2">{{ (collect($order->details->recipients)->count() > 1)? 'Adresses destinataires' : 'Adresse destinataire' }}</div>
                                                        <div class="grid grid-cols-1 md:grid-cols-2">
                                                            @foreach($order->details->recipients as $recipient)
                                                                <div class="relative border border-gray-200 rounded-[14px] p-3 md:p-6 text-sm shadow-md shadow-gray-200/20 {{ (collect($order->details->recipients)->count() === 1) ? 'col-span-2' : '' }}">
                                                                    <div>@include('components.address', ['person' => (array)$recipient])</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="pb-6">
                                                        <div class="text-sm font-semibold pb-2">Adresse expéditeur</div>
                                                        <div class="grid grid-cols-1 md:grid-cols-2">
                                                            @foreach($order->details->senders as $sender)
                                                                <div class="relative border border-gray-200 rounded-[14px] p-3 md:p-6 text-sm shadow-md shadow-gray-200/20 {{ (collect($order->details->senders)->count() === 1) ? 'col-span-2' : '' }}">
                                                                    <div>@include('components.address', ['person' => (array)$sender])</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="pb-6">
                                                        <div class="text-sm font-semibold pb-2">Mes documents</div>
                                                        <div class="grid grid-cols-1 gap-1.5">
                                                            @foreach($order->details->documents as $index => $document)
                                                                <div
                                                                    class="flex items-center bg-[#64605e] text-white rounded-[7px] h-[40px] gap-2 px-4 text-sm shadow-document"
                                                                    x-on:click.prevent="previewUrl('{{ route('frontend.account.preview.document', ['id' => auth()->guard('site')->user()->id, 'path' => $document->path, 'filename' => $document->readable_file_name]) }}', '{{ $document->readable_file_name }}')"
                                                                >
                                                                    <div class="flex-auto text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $document->readable_file_name }}</div>
                                                                    <div class="pl-2 flex items-center justify-end">
                                                            <span class="cursor-pointer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14" class="h-4 stroke-current">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.23 6.246c.166.207.258.476.258.754 0 .279-.092.547-.258.754C12.18 9.025 9.79 11.5 7 11.5c-2.79 0-5.18-2.475-6.23-3.746A1.208 1.208 0 0 1 .512 7c0-.278.092-.547.258-.754C1.82 4.975 4.21 2.5 7 2.5c2.79 0 5.18 2.475 6.23 3.746Z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                                </svg>
                                                            </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold pb-2">Votre commande</div>
                                                        <div class="border border-gray-200 rounded-[14px] p-3 md:p-6 shadow-md shadow-gray-200/20 divide-y divide-gray-100 text-sm">
                                                            <div class="flex gap-3 items-center divide-x divide-gray-100">
                                                                <div class="py-1 text-xs flex-auto font-semibold">Désignation</div>
                                                                <div class="py-1 hidden sm:block text-xs flex-none w-20 text-right font-semibold">P.U.</div>
                                                                <div class="py-1 hidden sm:block text-xs flex-none w-8 text-right font-semibold">Qté</div>
                                                                <div class="py-1 text-xs flex-none w-20 text-right font-semibold">Total</div>
                                                            </div>
                                                            @foreach($order->options as $option)
                                                                @if($option->name === 'color_print' || $option->name === 'black_print')
                                                                    <div class="flex gap-3 items-stretch divide-x divide-gray-100">
                                                                        <div class="py-1 flex-auto leading-tight">Impression 1<sup>ère</sup> page {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $order->options, true) ? 'R°V°' : '' }}</div>
                                                                        <div class="py-1 hidden sm:block flex-none w-20 text-right">@price($accounting->withSubscription($option->price[0], $order->with_subscription))</div>
                                                                        <div class="py-1 hidden sm:block flex-none w-8 text-right">{{ $number_of_recipients }}</div>
                                                                        <div class="py-1 flex-none w-20 text-right">@price($accounting->withSubscription($option->price[0] * $number_of_recipients, $order->with_subscription))</div>
                                                                    </div>
                                                                    @if($number_of_pages > 1)
                                                                        <div class="flex gap-3 items-stretch divide-x divide-gray-100">
                                                                            <div class="py-1 flex-auto leading-tight">Impression page suivante {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $order->options, true) ? 'R°V°' : '' }}</div>
                                                                            <div class="py-1 hidden sm:block flex-none w-20 text-right">@price($accounting->withSubscription($option->price[1], $order->with_subscription))</div>
                                                                            <div class="py-1 hidden sm:block flex-none w-8 text-right">{{ ($number_of_pages - 1) * $number_of_recipients }}</div>
                                                                            <div class="py-1 flex-none w-20 text-right">@price($accounting->withSubscription($option->price[1] * ($number_of_pages - 1) * $number_of_recipients, $order->with_subscription))</div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                            <div class="text-xs font-semibold py-1">Affranchissement</div>
                                                            <div class="flex gap-3 items-stretch divide-x divide-gray-100">
                                                                <div class="py-1 flex-auto leading-tight">Affr. {{ $weightFold }}g : {{ Str::lower($order->postage->label()) }}</div>
                                                                <div class="py-1 flex-none hidden sm:block w-20 text-right">@price($accounting->withSubscription($priceFold, $order->with_subscription))</div>
                                                                <div class="py-1 flex-none hidden sm:block w-8 text-right">{{ $number_of_recipients }}</div>
                                                                <div class="py-1 flex-none w-20 text-right">@price($accounting->withSubscription($priceFold * $number_of_recipients, $order->with_subscription))</div>
                                                            </div>
                                                            <div class="text-xs font-semibold py-1">Options complémentaires</div>
                                                            @foreach($order->options as $option)
                                                                @if($option->name === 'receipt' || $option->name === 'sms_notification')
                                                                    <div class="flex gap-3 items-stretch divide-x divide-gray-100">
                                                                        @if($option->name === 'receipt')
                                                                            <div class="py-1 flex-auto leading-tight">Justificatif d'accusé réception</div>
                                                                        @elseif($option->name === 'sms_notification')
                                                                            <div class="py-1 flex-auto leading-tight">Notification SMS</div>
                                                                        @endif
                                                                        <div class="py-1 hidden sm:block flex-none w-20 text-right">@price($accounting->withSubscription($option->price, $order->with_subscription))</div>
                                                                        <div class="py-1 hidden sm:block flex-none w-8 text-right">{{ $number_of_recipients }}</div>
                                                                        <div class="py-1 flex-none w-20 text-right">@price($accounting->withSubscription($option->price * $number_of_recipients, $order->with_subscription))</div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if($order->with_subscription && $loop->first)
                                                                <div class="flex gap-3 items-stretch divide-x divide-gray-100">
                                                                    <div class="py-1 flex-auto leading-tight">service accès+ offert pendant 15 jours</div>
                                                                    <div class="py-1 hidden sm:block flex-none w-20 text-right">@price(0)</div>
                                                                    <div class="py-1 hidden sm:block flex-none w-8 text-right">1</div>
                                                                    <div class="py-1 flex-none w-20 text-right">@price(0)</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endunless
                            </div>
                            {{--
                            @if(auth()->guard('site')->user()->subscription)
                                <div x-show="tab === 'mon-abonnement'" x-cloak class="flex flex-col gap-y-6">
                                    <livewire:subscribe-information-form/>
                                </div>
                            @endif
                            --}}
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>
</x-layouts.app>

@push('scripts')
    <script src="https://documentservices.adobe.com/view-sdk/viewer.js"></script>
@endpush

