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
                <div>
                    <div class="text-lg md:text-2xl font-semibold leading-tight">
                        -
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
