<?php

use function Laravel\Folio\{name};
name('pages.plan-site');

$categories = \App\Models\Category::with('templates')->get();
$brands = \App\Models\Brand::where('status', \App\Enums\PageStatus::VISIBLE)->get();
$guides = \App\Models\Guide::all();

?>

<x-layouts.app>
    <x-slot:head>
        <title>Plan du site - {{ config('app.name') }}</title>
        <meta name="description" content="Retrouvez toutes les marques et modèles de lettres dédiés à vos résiliations de contrats avec stop-contrat.com"/>
        <meta name="robots" content="index, noarchive, nocache, imageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-24 md:pt-32">
        <div class="relative bg-white">
            <livewire:search-brand/>
            <div class="max-w-screen-xl mx-auto pt-20 pb-12 md:py-24 px-6 flex gap-6 md:gap-12 flex-col">
                <div class="text-3xl leading-tight md:text-5xl font-semibold w-full">
                    Plan du site
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
                            @foreach($categorie->templates as $template)
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
                <div>
                    <div class="text-lg md:text-2xl font-semibold leading-tight">
                        Les marques
                    </div>
                    <div class="pt-3 md:pt-6 grid grid-cols-1 md:grid-cols-3 gap-x-12">
                        @foreach($brands as $brand)
                            <div  class="border-b border-gray-200">
                                <a
                                    href="{{ route('frontend.template.edit', ['slug' => $brand->slug]) }}"
                                    class="hover:bg-gray-50 hover:text-blue-700 text-base py-2 flex justify-between w-full items-center"
                                >
                                    {{ $brand->name }}
                                    <svg class="flex-none fill-blue-700" width="8" height="15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.581.287A.86.86 0 00.931 0C.416 0 0 .439 0 .996c0 .278.11.531.29.726l5.48 5.772-5.48 5.755c-.18.194-.29.456-.29.726 0 .557.415.995.932.995a.86.86 0 00.65-.286l6.09-6.414c.218-.22.32-.49.328-.785 0-.295-.11-.548-.329-.776L1.581.287z"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="text-lg md:text-2xl font-semibold leading-tight">
                        Les guides
                    </div>
                    <div class="pt-3 md:pt-6 grid grid-cols-1">
                        @foreach($guides as $guide)
                            <div  class="border-b border-gray-200">
                                <a
                                    href="{{ route('guides.article', ['guide' => $guide->slug]) }}"
                                    class="hover:bg-gray-50 hover:text-blue-700 text-base py-2 flex justify-between w-full items-center"
                                >
                                    {{ $guide->title }}
                                    <svg class="flex-none fill-blue-700" width="8" height="15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.581.287A.86.86 0 00.931 0C.416 0 0 .439 0 .996c0 .278.11.531.29.726l5.48 5.772-5.48 5.755c-.18.194-.29.456-.29.726 0 .557.415.995.932.995a.86.86 0 00.65-.286l6.09-6.414c.218-.22.32-.49.328-.785 0-.295-.11-.548-.329-.776L1.581.287z"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
