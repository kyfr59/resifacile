<x-layouts.app>
    <x-slot:head>
        <title>Suivre votre courrier en ligne | Resifacile</title>
        <meta name="description" content="Suivez facilement l’acheminement de votre courrier grâce à votre numéro de suivi La Poste et consultez les principales étapes de sa distribution."/>
        <meta name="robots" content="{{ $trackingNumber ? 'noindex' : 'index' }}, follow"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <form action="{{ route('tracking.number') }}" method="POST">@csrf
    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] md:pt-32">
        <div class="relative bg-white">
            <div class="max-w-screen-xl mx-auto pt-2 pb-2 md:py-12 px-4 flex gap-6 md:gap-12 flex-col">

                <!-- Header -->
                <div class="relative overflow-visible">
                <div class="pointer-events-none absolute right-0 -top-4 hidden select-none sm:block">
                    <svg width="220" height="140" viewBox="0 0 220 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="150" cy="40" rx="60" ry="22" fill="#E2E8F0" opacity="0.6" />
                    <ellipse cx="190" cy="55" rx="30" ry="14" fill="#E2E8F0" opacity="0.5" />
                    <path d="M20 110 C 60 60, 90 100, 120 70 S 170 40, 195 45" stroke="#CBD5E1" stroke-width="2" stroke-dasharray="4 6" fill="none" />
                    <circle cx="20" cy="110" r="4" fill="#94A3B8" />
                    </svg>
                    <div class="absolute right-10 top-6 flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="absolute right-1 top-16 flex h-16 w-20 rotate-6 items-center justify-center rounded-xl bg-blue-600 shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <div class="absolute right-0 top-28 flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500 text-white ring-4 ring-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                </div>

                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-[2.75rem]">
                    Suivre votre courrier
                </h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-slate-500">
                    Retrouvez ici les principales étapes de préparation et d'acheminement de votre courrier.
                    Les informations sont mises à jour automatiquement à partir des données de suivi.
                </p>
                </div>

                <!-- Search card -->
                <div class="mt-8 rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <label for="tracking" class="mb-2 block text-sm font-medium text-slate-500">
                    Numéro de suivi La Poste
                </label>
                <div class="flex flex-col gap-3 sm:flex-row">
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
                    <button class="flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-[15px] font-medium text-white transition hover:bg-blue-700 active:bg-blue-800" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Suivre l'envoi
                    </button>
                </div>
                </div>

                @if(isset($tracking) && $tracking['success'] === false)

                    <div class="border-red-200 text-red-700 rounded-xl p-2 pt-6">
                        <p class="font-semibold">
                            {{ $tracking['message']}}
                        </p>
                    </div>

                @elseif(!empty($tracking))

                    <!-- Status card -->
                    <div class=" mt-6 rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                @php
                                    echo $tracking['steps'][$currentStep - 1]['word'];
                                @endphp
                            </span>
                            <h2 class="mt-2 text-xl font-semibold text-slate-900">
                            {{ $tracking['lastStatusLabel'] }}
                            </h2>
                            <p class="mt-1 max-w-md text-[15px] leading-relaxed text-slate-500">
                            {{ $tracking['lastStatusDescription'] }}
                            </p>
                        </div>
                        </div>

                        <div class="flex shrink-0 flex-col gap-2.5 text-sm text-slate-500 sm:items-end">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586a2 2 0 0 0-2.828 0L2.586 9.758a2 2 0 0 0 0 2.828l8.828 8.828a2 2 0 0 0 2.828 0l7.172-7.172a2 2 0 0 0 0-2.828z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                            <span>Numéro de suivi : <span class="font-medium text-slate-700">{{ $trackingNumber ?? '' }}</span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Dernière mise à jour : <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($tracking['events'][0]['date'])->format('d/m/Y H:i') }}</span></span>
                        </div>
                        </div>
                    </div>

                    <!-- Stepper -->
                    @php
                        $totalSteps = count($tracking['steps']);

                        $lastTrueIndex = 0;
                        foreach ($tracking['steps'] as $index => $step) {
                            if ($step['status'] == 'true') {
                                $lastTrueIndex = $index + 1; // +1 car on veut aller jusqu'au CENTRE du cercle
                            }
                        }

                        $progressPercent = $totalSteps > 0
                            ? ((($lastTrueIndex - 0.5) / $totalSteps) * 100)
                            : 0;

                        if($progressPercent == 90) $progressPercent = 100;
                    @endphp

                    <div class="mt-9 px-1">
                        <div class="relative flex items-start justify-between">

                            {{-- Ligne de fond grise --}}
                            <div class="absolute left-4 right-4 top-4 h-0.5 -translate-y-1/2 bg-slate-200"></div>

                            {{-- Ligne rouge dynamique --}}
                            <div
                                class="absolute left-4 top-4 h-0.5 -translate-y-1/2 bg-blue-600"
                                style="width: calc({{ $progressPercent }}% - 1rem);"
                            ></div>

                            @foreach($tracking['steps'] as $step)
                                <div class="relative z-10 flex sm:w-1/4 flex-col items-center text-center">

                                    @if ($step['state'] == 'done')
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm shadow-blue-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </div>
                                    @elseif ($step['state'] === 'active')
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full border-[3px] border-blue-600 bg-white">
                                            <div class="h-2.5 w-2.5 rounded-full bg-blue-600"></div>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-full border-2 border-slate-200 bg-white"></div>
                                    @endif

                                    <span class="mt-3 hidden text-sm font-semibold text-slate-800 sm:block">{{ $step['word'] }}</span>
                                    <span class="mt-0.5 hidden text-sm sm:block {{ $step['status'] == 'true' ? 'text-blue-600' : 'text-slate-400' }}">
                                        {{ $step['state'] == 'done' ? 'Terminé' : ($step['state'] == 'active' ? 'En cours' : 'A venir') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="ml-2 mt-4 block font-bold sm:hidden">
                            Etape {{ $currentStep }}/5 - <span class="text-blue-600">{{ $tracking['steps'][$currentStep > 0 ? $currentStep - 1 : 0]['word'] }}</span>
                        </div>
                    </div>

                    <!-- History card -->
                    <div class="mt-6 rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
                    <h3 class="text-lg font-semibold text-slate-900">Historique de l'acheminement</h3>

                    <div class="mt-5">

                        @foreach($tracking['events'] as $event)
                           <div class="relative flex gap-4 pb-7">
                                <div class="absolute left-[13px] top-7 h-[calc(100%-1rem)] w-px bg-slate-200"></div>
                                @if ($loop->first)
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-[3px] border-blue-600 bg-white">
                                        <div class="h-2 w-2 rounded-full bg-blue-600"></div>
                                    </div>
                                @else
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </div>
                                @endif
                                <div class="-mt-0.5 flex flex-1 flex-col justify-between gap-1 sm:flex-row sm:items-start {{ $loop->first ? 'rounded-xl bg-blue-50/60 px-3 py-2' : '' }}">
                                    <div>
                                        <p class="text-[15px] font-semibold {{ $loop->first ? 'text-blue-700' : 'text-slate-800' }} ">{{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}</p>
                                        <p class="mt-0.5 max-w-sm text-sm leading-relaxed text-slate-500">{{ $event['label'] }}</p>
                                    </div>
                                    <span class="shrink-0 text-sm text-slate-400">Code de suivi : {{ $event['code'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Footer action -->
                    <div class="mt-7 flex justify-center pb-">
                        <a href="{{ route('tracking.number') }}" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-[15px] font-medium text-blue-600 shadow-sm transition hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
                            Suivre un autre courrier
                        </a>
                    </div>
                @endif
                </div>

            </div>
        </div>
    </form>
</div>
</x-layouts.app>
