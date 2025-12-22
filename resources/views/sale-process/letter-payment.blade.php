<x-layouts.app>
    <x-slot:head>
        <title>Payez & Envoyez - {{ config('app.name') }}</title>
        <meta name="description" content="Payez & Envoyez"/>
        <?php /* <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/> */ ?>
        <meta name="robots" content="noindex, nofollow, noarchive, noimageindex">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 gap-3 md:gap-6 w-full max-w-screen-lg mx-auto py-12 md:py-24 px-6">
                <x-sale-process.file-arianne etape="reglement"/>
                <div>
                    <h1 class="text-2xl md:text-4xl text-center text-gradient">
                        <strong class="gradient">Payez</strong> & envoyez
                    </h1>
                </div>
                <livewire:letter-payment-form/>
                @if(config('payment.bypass_payment'))
                    <livewire:letter-fake-payment-buttons />
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>

