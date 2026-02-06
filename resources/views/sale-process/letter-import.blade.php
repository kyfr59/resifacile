<x-layouts.app>
    <x-slot:head>
        <title>Importez vos documents - {{ config('app.name') }}</title>
        <meta name="description" content="Importez vos documents"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 gap-3 md:gap-6 w-full max-w-screen-lg mx-auto py-12 md:py-24 px-6">
                <x-sale-process.file-arianne etape="remplir"/>
                <div>
                    <h1 class="text-2xl md:text-4xl text-center text-gradient">
                        <strong class="gradient">Importer</strong> vos documents
                    </h1>
                    <div class="text-gray-400">Format autorisé le PDF, moins de 20Mo</div>
                </div>
                <livewire:letter-import-form/>
            </div>
        </section>
    </div>
</x-layouts.app>
