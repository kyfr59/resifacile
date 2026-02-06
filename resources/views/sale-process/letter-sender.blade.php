<x-layouts.app>
    <x-slot:head>
        <title>Coordonnées expéditeur - {{ config('app.name') }}</title>
        <meta name="description" content="Coordonnées expéditeur"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 gap-3 md:gap-6 w-full max-w-screen-lg mx-auto py-12 md:py-24 px-6">
                <x-sale-process.file-arianne etape="formule"/>
                <div class="px-6 sm:px-0">
                    <h1 class="text-2xl md:text-4xl text-center text-gradient pb-6">
                        Indiquez <strong>vos coordonnées</strong>
                    </h1>
                </div>
                <livewire:letter-sender-form/>
                <div class="text-[.7rem] text-justify">
                    Media Group Sas collecte vos données personnelles afin de gérer et suivre vos contrats, ainsi que de traiter et suivre vos commandes. Vous avez certains droits à votre disposition, notamment le droit d'accès, de rectification, de mise à jour, d'effacement, et le droit de retirer votre consentement à tout moment. De plus, vous avez le droit à la limitation du traitement, le droit d'opposition, le droit à la portabilité, le droit de définir des directives concernant vos données après votre décès, et enfin, le droit d'introduire une réclamation auprès de la CNIL.
                    Pour obtenir davantage d'informations sur la protection de vos données personnelles, les traitements effectués par Media Group Sas, ainsi que les démarches pour exercer vos droits, veuillez consulter notre <a href="{{ route('pages.content', ['page' => 'grpd']) }}">politique de confidentialité</a>.
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
