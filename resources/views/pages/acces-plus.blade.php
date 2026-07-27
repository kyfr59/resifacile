<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('pages.acces-plus');

$page = \App\Models\Page::find(9);
$guides = \App\Models\Guide::orderByDesc('created_at')->limit(3)->get();

?>

<x-layouts.app>
    <x-slot:head>
        <title>{{ $page->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $page->seo_title }}"/>
        <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <div class="bg-white">
            <div class="max-w-screen-lg mx-auto pt-24 pb-12 md:px-6 grid grid-cols-[2fr_1fr] gap-12">
                <div>
                    <h1 class="text-4xl leading-tight md:text-5xl font-semibold pb-4 w-full">
                        {{ $page->title }}
                    </h1>
                    <article class="article w-full">
                        {!! Markdown::parse($page->article) !!}
                    </article>
                </div>
                <div class="flex flex-col gap-6">
                    <div class="bg-gradient-to-tr from-[#fb29cd] to-[#fdc51d] p-6 rounded-xl leading-tight flex justify-center flex-col gap-3">
                        <div class="text-white text-2xl font-semibold">Envoyez votre lettre de résiliation, depuis chez vous !</div>
                        <div class="text-right font-semibold text-6xl leading-tight">
                            <div class="text-white font-light text-lg leading-none">à partir de</div>
                            <div class="text-white leading-none relative -right-4">
                                2,22€<sup class="font-light text-4xl">*</sup>
                            </div>
                        </div>
                        <a href="/trouvez-une-marque" class="h-14 px-12 bg-blue-700 text-white justify-center rounded-lg text-lg inline-flex items-center">J'en profite</a>
                    </div>
                    <div class="text-[11px] text-justify text-gray-600">
                        L’offre « Accès+ » vous permet d’envoyer vos courriers depuis le site resifacile.fr en bénéficiant d’une réduction de 50 % sur vos quinze (15) premiers envois chaque mois, d’un accès à plus de 1 200 modèles de courriers et de l’archivage de vos commandes. Cette offre tarifaire, dite « Accès+ », est valable uniquement dans le cadre de la souscription d’un abonnement sans engagement, dont les trois (3) premiers jours sont offerts, puis facturé trente-neuf euros et quatre-vingt-dix centimes (39,90 €) par mois, conformément à nos conditions générales de vente. Cet abonnement est résiliable à tout moment. Si vous ne souhaitez pas souscrire à notre service « Accès+ », vous pouvez choisir l’une de nos offres unitaires. Vous disposez d’un délai de rétractation de quatorze (14) jours à compter de la conclusion du contrat d’abonnement « Accès+ ». Pendant ce délai, vous pouvez exercer votre droit de rétractation à l’adresse suivante : https://resifacile.fr/se-desabonner.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-50">
        <div class="max-w-screen-xl mx-auto py-12 md:py-24 pb-12 px-6 flex flex-col gap-6">
            <h2 class="text-4xl leading-tight md:text-5xl text-center font-semibold pb-4 w-full">
                Derniers articles
            </h2>
            <div class="grid grid-cols-3 gap-12 w-full">
                @foreach ($guides as $guide)
                    <a
                        href="{{ route('guides.article', ['guide' => $guide]) }}"
                        class="bg-white flex flex-col rounded-xl shadow-lg overflow-hidden"
                    >
                        @if($guide->visual)
                            <img src="{{ asset('storage/' . $guide->visual) }}" alt="{{ $guide->title }}" class="aspect-video object-cover">
                        @endif
                        <div class="p-6 flex-1">
                            <div class="text-lg md:text-xl md:leading-tight font-semibold mb-3">
                                {{ $guide->title }}
                            </div>
                            <div class="text-sm leading-normal flex-none">
                                {{ Str::words(strip_tags(Markdown::parse($guide->article)), 20, '…') }}
                            </div>
                        </div>
                        <div class="text-center p-6">
                            <div class="h-10 px-6 border-2 text-blue-700 rounded-full text-sm inline-flex items-center">
                                Lire l'article
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
