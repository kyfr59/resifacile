<x-layouts.app>
    <x-slot:head>
        <title>Formules d'envoi - {{ config('app.name') }}</title>
        <meta name="description" content="Formules d'envoi"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-white">
            <div class="grid grid-cols-1 gap-6 w-full max-w-screen-lg mx-auto py-12 md:py-24 px-6">
                <x-sale-process.file-arianne etape="formule"/>
                <div>
                    <h1 class="text-3xl md:text-4xl text-center">
                        Sélectionnez votre <strong class="text-gradient">affranchissement</strong> accès+
                    </h1>
                    <div class="text-center text-gray-400 text-sm">
                        Et bénéficiez de nos services accès+ offert pendant 15 jours*, puis 39,90€/mois sans engagement
                    </div>
                </div>
                <livewire:select-postage-with-subscription postage-page="true"/>
                <div class="flex flex-col gap-3">
                    @include('components.sections.call')
                    <div class="text-[.6rem] sm:text-[.7rem] justify-between text-justify"><span>*</span>{{ $subscription->mention_one }}</div>
                </div>
            </div>
        </section>
        <section class="bg-gradient-to-r from-[#fb29cd] to-[#fdc51d] text-white">
            <div class="w-full max-w-7xl mx-auto p-1.5  sm:py-3 sm:px-12 md:px-16">
                <div class="text-sm md:text-base sm:text-lg lg:text-xl text-center flex gap-3 items-center justify-center leading-tight">
                    Tous nos courriers sont distribués par La Poste
                </div>
            </div>
        </section>
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 gap-6 w-full max-w-screen-lg mx-auto pt-8 pb-12 md:pt-20 md:pb-24 px-6">
                <div class="text-2xl text-center pb-6">
                    Juste une résiliation, nos offres unitaires, <strong class="text-gradient">simple & rapide</strong> !
                </div>
                <livewire:select-postage postage-page="true"/>
            </div>
        </section>
    </div>
</x-layouts.app>
