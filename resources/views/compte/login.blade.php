<x-layouts.app>
    <x-slot:head>
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="Envoyez vos courriers sans vous déplacer ! — {{ config('app.name') }}"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex" />
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="w-full max-w-lg mx-auto py-12 md:py-16 px-6 md:px-9">
                <form
                    method="post"
                    class="bg-white rounded-xl shadow-xl overflow-hidden"
                >
                    @csrf
                    <div class="grid grid-cols-1 gap-6 p-8 md:p-12">
                        <div class="text-center text-gradient text-lg font-semibold leading-tight">Connectez-vous à votre compte</div>
                        <div class="pb-2 text-sm text-justify">Renseignez l'email que vous avez utilisé lors de votre commande. Vous recevrez un message contenant un lien sécurisé qui vous permettra d'accéder directement à votre compte.</div>
                        <div class="flex flex-col gap-2">
                            <label
                                for="email"
                                class="text-xs">Email</label>
                            <input
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="email"
                                id="email"
                                class="w-full border-2 border-gray-200 px-3 rounded-xl outline-none h-14  @error('email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
                            />
                            @error('email')
                                <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
                            @endif
                        </div>
                        <div class="flex flex-col-reverse md:flex-row justify-center gap-2 items-end md:items-center">
                            <button class="w-full bg-blue-700 text-white h-14 px-6 rounded-lg flex items-center justify-center text-lg leading-tight" type="submit">Recevoir l'email de connexion</button>
                        </div>
                    </div>
                    @if(request()->session()->has('success'))
                        <div class="bg-emerald-600 text-white p-6 text-center">
                            {{ request()->session()->get('success') }}
                        </div>
                    @endif
                </form>
            </div>
        </section>
    </div>
</x-layouts.app>
