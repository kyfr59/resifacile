<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('guides.article');

?>

<x-layouts.app>
    <x-slot:head>
        <title>{{ $guide->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $guide->seo_title }}"/>
        <meta name="robots" content="index, noarchive, nocache, noimageindex"/>
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
                    "name": "Guides",
                    "item": "{{ route('guides.index') }}"
                },{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "{{ $guide->title }}",
                    "item": "{{ url()->current() }}"
                }]
            }
        </script>
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-24">
       <div class="bg-white">
           <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex flex-col gap-6 md:gap-12">
               <h1 class="text-4xl leading-tight md:text-5xl font-semibold w-full">
                   {{ $guide->title }}
               </h1>
               @if($guide->visual)
                   <img src="{{ Storage::disk('public')->url($guide->visual) }}" alt="{{ $guide->title }}" class="shadow-lg rounded-xl aspect-[1000/256] object-cover">
               @endif
               <article class="article w-full md:w-4/5">
                   {!! Markdown::parse($guide->article) !!}
               </article>
           </div>
       </div>
    </div>
</x-layouts.app>
