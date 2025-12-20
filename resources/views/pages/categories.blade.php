<?php

use App\Models\Page;
use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('pages.categories');

$page = Page::find(14);
$categories = \App\Models\Category::with('templates')->get();

?>

<x-layouts.app>
    <x-slot:head>
        <title>{{ $page->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $page->seo_title }}"/>
        <?php /* <meta name="robots" content="index, noarchive, nocache, imageindex"/> */ ?>
        <meta name="robots" content="noindex, nofollow, noarchive, noimageindex">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [{
                    "@type": "ListItem",
                    "position": 0,
                    "name": "Accueil",
                    "item": "{{ url('/') }}"
                },{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Lettre de résiliation",
                    "item": "{{ url()->current() }}"
                }]
            }
        </script>
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-24 md:pt-32">
        <div class="relative bg-white">
            <livewire:search-brand/>
            <div class="max-w-screen-xl mx-auto pt-20 pb-12 md:py-24 px-6 flex gap-6 md:gap-12 flex-col">
                <div class="text-3xl leading-tight md:text-5xl font-semibold w-full">
                    Modèles de lettre
                </div>
                @foreach($categories as $categorie)
                    <div>
                        <a
                            href="{{ route('frontend.template.edit', ['slug' => $categorie->slug]) }}"
                            class="text-lg md:text-2xl font-semibold leading-tight"
                        >
                            {{ $categorie->name }}
                        </a>
                        <div class="pt-3 md:pt-6 grid grid-cols-1 md:grid-cols-2 gap-x-12">
                            @foreach ($categorie->templates as $template)
                                <div  class="border-b border-gray-200">
                                    <a
                                        href="{{ route('frontend.template.edit', ['slug' => $template->slug]) }}"
                                        class="hover:bg-gray-50 hover:text-blue-700 text-base py-2 flex justify-between w-full items-center"
                                    >
                                        {{ $template->name }}
                                        <svg class="flex-none fill-blue-700" width="8" height="15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.581.287A.86.86 0 00.931 0C.416 0 0 .439 0 .996c0 .278.11.531.29.726l5.48 5.772-5.48 5.755c-.18.194-.29.456-.29.726 0 .557.415.995.932.995a.86.86 0 00.65-.286l6.09-6.414c.218-.22.32-.49.328-.785 0-.295-.11-.548-.329-.776L1.581.287z"/></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="bg-gray-50">
        <div class="article max-w-screen-lg w-full mx-auto py-12 md:py-24 px-6 flex flex-col gap-6 md:gap-12">
            <h1 class="text-3xl md:text-5xl font-semibold pb-4 w-full leading-tight">
                {{ $page->title }}
            </h1>
            <div class="w-full md:w-4/5">
                {!! Markdown::parse($page->article) !!}
            </div>
        </div>
    </div>

</x-layouts.app>
