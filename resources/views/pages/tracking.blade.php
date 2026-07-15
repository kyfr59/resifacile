<x-layouts.app>
    <x-slot:head>
        <title>Suivre votre envoi - {{ config('app.name') }}</title>
        <meta name="description" content="Retrouvez toutes les marques et modèles de lettres dédiés à vos résiliations de contrats avec resifacile.fr"/>
        <meta name="robots" content="index, noarchive, nocache, imageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-24 md:pt-32">
        <div class="relative bg-white">
            <div class="max-w-screen-xl mx-auto pt-20 pb-12 md:py-24 px-6 flex gap-6 md:gap-12 flex-col">
                <div class="text-3xl leading-tight md:text-5xl font-semibold w-full">
                    Suivre votre envoi
                </div>
                <form action="{{ route('tracking.number') }}" method="POST">
                    @csrf

                    <div>
                        <div class="col-span-1 md:col-span-2 flex flex-col">
                            <label for="tracking_number" class="mb-2 text-xs flex gap-2">
                                Numéro de suivi La Poste
                                <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span>
                            </label>

                             <input
                                type="text"
                                placeholder="Saisissez votre numéro de suivi"
                                id="tracking_number"
                                name="tracking_number"
                                value="{{ $trackingNumber ?? '' }}"
                                class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
                                required
                                oninvalid="this.setCustomValidity('Veuillez saisir votre numéro de suivi La Poste.')"
                                oninput="this.setCustomValidity('')"
                            >
                        </div>

                        <div class="pb-6 flex flex-col-reverse sm:flex-row gap-6 justify-between mt-6">
                            <button class="mt-5 w-auto bg-blue-700 text-white h-14 px-6 rounded-xl flex items-center justify-center" type="submit">Suivre l'envoi</button>
                        </div>

                        @if(isset($tracking) && $tracking['success'] === false)

                            <div class="border-red-200 text-red-700 rounded-xl p-2 pt-6">
                                <p class="font-semibold">
                                    {{ $tracking['message']}}
                                </p>
                            </div>

                        @elseif(!empty($tracking))
                            <div class="space-y-4 mt-6 pl-2">
                                <h2 class="text-xl font-semibold mb-4">
                                    Historique du suivi
                                </h2>
                                <div class="relative ml-4 mt-8">
                                    <!-- ligne verticale -->
                                    <div class="absolute left-3 top-0 h-full w-0.5 bg-gray-200"></div>

                                    @foreach($tracking['events'] as $event)
                                        <div class="relative flex gap-6 pb-10">

                                            <!-- Point sur la timeline -->
                                            <div class="
                                                relative z-10 flex items-center justify-center
                                                w-6 h-6 rounded-full border-4
                                                {{ $loop->first
                                                    ? 'bg-blue-600 border-blue-100'
                                                    : 'bg-gray-300 border-white'
                                                }}
                                            ">
                                            </div>

                                            <!-- Contenu -->
                                            <div class="
                                                flex-1 rounded-xl p-5 border
                                                {{ $loop->first
                                                    ? 'bg-gray-100 border-blue-300 shadow-md'
                                                    : 'bg-white border-gray-200 opacity-70'
                                                }}
                                            ">
                                                <div class="flex justify-between items-start gap-4">
                                                    <h3 class="font-semibold">
                                                        {{ $event['code'] }}
                                                    </h3>

                                                    <span class="text-xs text-gray-500 whitespace-nowrap">
                                                        {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>

                                                <p class="mt-2 text-gray-700">
                                                    {{ $event['label'] }}
                                                </p>

                                                @if($loop->first)
                                                    <span class="inline-flex mt-3 px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                        Dernier statut
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
