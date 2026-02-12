<div class="mx-auto max-w-screen-xl px-6 h-16 md:h-24 flex items-center justify-between">
    <a href="{{ route('pages.index') }}">
       <img src="{{ asset('images/logo-header.jpg') }}" class="pt-5" width="225" height="71" alt="{{ config('app.name') }}">
    </a>
    <button
        class="block md:hidden w-6 h-8 text-gray-800"
        x-on:click.prevent="showMenu = true"
        aria-label="menu"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="h-6">
            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13.5 2H.5M13.5 7H.5M13.5 12H.5"/>
            </g>
        </svg>
    </button>
    <nav class="hidden md:flex items-center justify-center gap-4">
        <a href="{{ route('pages.categories') }}">Modèles de lettre</a>
        <a href="{{ route('pages.trouvez-une-marque') }}">Trouvez une marque</a>
        <a href="https://www.laposte.fr/outils/suivre-vos-envois" target="_blank">Suivre un envoi</a>
        <a href="{{ route('pages.acces-plus') }}">Accès+</a>
        <a href="{{ route('guides.index') }}">Guides</a>
        <a class="font-semibold" href="{{ route('login') }}">Se connecter</a>
    </nav>
</div>
