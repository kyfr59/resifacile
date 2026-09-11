<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('guides.index');

$guides = \App\Models\Guide::where('status', 'published')
    ->orderByDesc('created_at')
    ->paginate(36);

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

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <div class="bg-white">
            <div class="max-w-screen-xl mx-auto pt-12 md:pt-24 pb-8 md:pb-12 px-4 sm:px-6">

                <h1 class="text-3xl sm:text-4xl md:text-5xl leading-tight font-semibold pb-4">
                    Guides
                </h1>

                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-12 w-full">

                    @foreach ($guides as $guide)
                        <a
                            href="{{ route('guides.article', ['guide' => $guide]) }}"
                            class="flex flex-col rounded-xl shadow-lg overflow-hidden h-full"
                        >
                            @if($guide->visual)
                                <img
                                    src="{{ Storage::disk('public')->url($guide->visual) }}"
                                    alt="{{ $guide->title }}"
                                    class="w-full aspect-video object-cover"
                                >
                            @endif

                            <div class="p-4 sm:p-5 md:p-6 flex-1">
                                <div class="text-lg sm:text-xl leading-tight font-semibold mb-3">
                                    {{ $guide->title }}
                                </div>

                                <div class="text-sm leading-normal">
                                    {{ Str::words(strip_tags(Markdown::parse($guide->article)), 20, '…') }}
                                </div>
                            </div>

                            <div class="text-center p-4 sm:p-5 md:p-6">
                                <div class="h-10 px-5 sm:px-6 border-2 text-blue-700 rounded-full text-sm inline-flex items-center justify-center">
                                    Lire l'article
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <div class="w-full md:col-span-2 lg:col-span-3">
                        {{ $guides->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
