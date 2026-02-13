<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('guides.index');

$guides = \App\Models\Guide::orderByDesc('created_at')->paginate(36);

?>

<x-layouts.app>
    <x-slot:head>
        <title>Liste des guides - page {{ (!request()->has('page')) ? '1' : request()->input('page') }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }}"/>
        <link rel="canonical" href="{{ (!request()->has('page') || request()->input('page') == 1) ? request()->url() : request()->fullUrl() }}">
        <link rel="alternate" href="{{ (!request()->has('page') || request()->input('page') == 1) ? request()->url() : request()->fullUrl() }}" hreflang="fr">
        @if($guides->previousPageUrl())
            <link rel="prev" href="{{ $guides->previousPageUrl() }}">
        @endif
        @if($guides->nextPageUrl())
            <link rel="next" href="{{ $guides->nextPageUrl() }}">
        @endif
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-24">
        <div class="bg-white">
            <div class="max-w-screen-xl mx-auto pt-24 pb-12 md:px-6 flex justify-start flex-wrap">
                <h1 class="text-4xl leading-tight md:text-5xl font-semibold pb-4 w-full px-6">
                    Guides
                </h1>
                <div class="pt-6 grid grid-cols-3 gap-12 w-full px-6">
                    @foreach ($guides as $guide)
                        <a
                            href="{{ route('guides.article', ['guide' => $guide]) }}"
                            class="flex flex-col rounded-xl shadow-lg overflow-hidden"
                        >
                            @if($guide->visual)
                                <img src="{{ Storage::disk('public')->url($guide->visual) }}" alt="{{ $guide->title }}" class="aspect-video object-cover">
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
                    <div class="w-full md:col-span-3">
                        {{ $guides->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
