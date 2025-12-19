<x-layouts.app>
    <x-slot:head>
        <title>Récapitulatif de votre commande - {{ config('app.name') }}</title>
        <meta name="description" content="Récapitulatif de votre commande"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 md:gap-6 w-full max-w-5xl mx-auto py-12 md:py-24 px-6">
                <div>
                    <div class="text-xl md:text-2xl font-semibold">Sélectionnez vos options</div>
                    <h1 class="text-2xl md:text-4xl text-center text-gradient pb-6">
                        <strong class="gradient">Vérifiez</strong> votre commande
                    </h1>
                </div>
                <livewire:letter-validation-form/>
            </div>
        </section>
    </div>
</x-layouts.app>

