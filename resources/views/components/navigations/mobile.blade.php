<div
    class="fixed z-[99] bg-black inset-0 bg-opacity-95 grid grid-rows-[theme('spacing.20'),1fr,theme('spacing.40')]"
    x-show="showMenu"
    x-cloak
>
    <div class="w-full h-20 flex items-center justify-between gap-8 px-6 pb-4 bg-[#fff3ee]">
        <div>
            <a href="{{ route('pages.index') }}">
                <img src="https://resifacile.local/images/logo-header.png" class="pt-5" width="225" height="71" alt="Resifacile.fr">
            </a>
        </div>
        <div class="block">
            <button
                class="h-8 mt-5"
                x-on:click.prevent="showMenu = false"
                aria-label="menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="h-6 aspect-square">
                    <g fill="none" stroke="#444" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m13.5.5-13 13M.5.5l13 13"/>
                    </g>
                </svg>
            </button>
        </div>
    </div>
    <nav class="flex flex-col items-center justify-center gap-6 text-xl">
        <a href="{{ route('pages.categories') }}">Modèles de lettre</a>
        <a href="{{ route('pages.trouvez-une-marque') }}">Trouvez une marque</a>
        <a href="{{ route('tracking') }}">Suivre un envoi</a>
        <a href="{{ route('pages.acces-plus') }}">Accès+</a>
        <a href="{{ route('guides.index') }}">Guides</a>
    </nav>
    <div class="p-6 flex items-center justify-center">
        @guest
            <a href="{{ route('login') }}" class="h-8 w-auto pl-3 pr-2 rounded-full flex items-center justify-center gap-3 border border-gray-200">
                <span>Se connecter</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0.125 0.125 13.75 13.75" stroke-width="0.75" class="h-4 stroke-white">
                    <g>
                        <circle cx="7" cy="5.5" r="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path d="M2.73,11.9a5,5,0,0,1,8.54,0" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="7" cy="7" r="6.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
                    </g>
                </svg>
            </a>
        @else
            <a href="{{ route('auth.account') }}" class="h-8 w-auto pl-3 pr-2 rounded-full flex items-center justify-center gap-3 border border-gray-200">
                <span>Mon compte</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0.125 0.125 13.75 13.75" stroke-width="0.75" class="h-4 stroke-white">
                    <g>
                        <circle cx="7" cy="5.5" r="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path d="M2.73,11.9a5,5,0,0,1,8.54,0" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="7" cy="7" r="6.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
                    </g>
                </svg>
            </a>
        @endguest
    </div>
</div>
