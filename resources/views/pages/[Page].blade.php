<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('pages.content');

$guides = \App\Models\Guide::orderByDesc('created_at')->limit(3)->get();

?>

<x-layouts.app>
    <x-slot:head>
        <title>{{ $page->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $page->seo_title }}"/>
        <meta name="robots" content="index, noarchive, nocache, noimageindex"/>
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <div class="bg-white">
            <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex flex-col gap-6 md:gap-12">
                <h1 class="text-4xl leading-tight md:text-5xl font-semibold w-full">
                    {{ $page->title }}
                </h1>
                <article class="article w-full md:w-4/5">
                    {!! Markdown::parse($page->article) !!}
                </article>
            </div>
        </div>
    </div>
    <div class="bg-gray-50">
        <div class="max-w-screen-xl mx-auto py-12 md:py-24 pb-12 px-6 flex flex-col gap-6 md:gap-12">
            <h2 class="text-4xl leading-tight md:text-5xl text-center font-semibold w-full">
                Derniers articles
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 w-full">
                @foreach ($guides as $guide)
                    <a
                        href="{{ route('guides.article', ['guide' => $guide]) }}"
                        class="bg-white flex flex-col rounded-xl shadow-lg overflow-hidden"
                    >
                        @if($guide->visual)
                            <img src="{{ Storage::disk('do')->url($guide->visual) }}" alt="{{ $guide->title }}" class="aspect-video object-cover">
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
    <div class="bg-white">
        <div class="max-w-screen-xl mx-auto py-12 md:py-24 px-6 flex justify-between flex-wrap">
            <div class="text-center md:text-left w-full text-xl md:text-2xl mb-4 md:mb-0 leading-none">Pourquoi choisir stop-contrat ?</div>
            <div class="text-center md:text-left w-full text-3xl leading-tight md:text-4xl font-semibold text-gradient pb-6">Les avantages de nos services</div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex flex-col justify-start items-center gap-3">
                    <svg width="151" height="147" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="65" cy="65" r="63" stroke="url(#paint0_linear)" stroke-width="4"></circle><circle opacity=".2" cx="84.5" cy="80.5" r="66.5" fill="url(#paint1_linear)"></circle><path d="M45 64v19.5a7 7 0 007 7h22a7 7 0 007-7v-34a7 7 0 00-7-7H52a7 7 0 00-7 7v.5" stroke="#14142B" stroke-width="4" stroke-linecap="round"></path><path stroke="#14142B" stroke-width="2" stroke-linecap="round" d="M53 52h19M53 59h19M53 66h15M53 73h15M67 80.2l2.333 2.8L74 77"></path><defs><linearGradient id="paint0_linear" x1="-17.798" y1="225.333" x2="189.003" y2="134.849" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset=".653" stop-color="#FDAD1F"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient><linearGradient id="paint1_linear" x1="18" y1="14" x2="178.329" y2="86.868" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient></defs></svg>
                    <div class="text-center font-semibold md:text-lg">
                        Des modèles de lettres prérédigés
                    </div>
                </div>
                <div class="flex flex-col justify-start items-center gap-3">
                    <svg width="148" height="147" fill="none" xmlns="http://www.w3.org/2000/svg"><circle opacity=".2" cx="81.5" cy="80.5" r="66.5" fill="url(#paint0_linear)"></circle><circle cx="65" cy="65" r="63" stroke="url(#paint1_linear)" stroke-width="4"></circle><path d="M83.447 42.347L59.881 64.609c-.199-1.867-1.372-8.143-2.088-10.03-.895-2.359-3.044-3.574-5.519-2.949-2.878.728-3.325 2.941-3.43 3.686-.297 2.113 1.342 4.575 1.044 8.261-.298 3.686-.15 1.032-2.834 7.962-2.685 6.93.448 11.794 1.79 13.564 1.342 1.769 6.115 6.34 11.037 10.468 4.922 4.128 14.468 2.211 17.6 0 3.132-2.212 8.352-7.667 9.844-9.141 1.491-1.475 2.386-3.097 1.044-5.75-1.074-2.123-3.312-2.543-7.756-.43 6.99-4.226 5.071-7.06 3.878-8.711-1.492-2.064-4.122-1.431-7.5 0 5.263-3.122 5.263-5.658 3.622-7.962-1.709-2.4-5.12-1.376-6.264-.884C79.17 58.27 88.995 49.217 89.71 48.392c.895-1.032 2.387-3.834 0-6.045-1.909-1.77-3.805-1.822-6.264 0z" stroke="#14142B" stroke-width="4" stroke-linejoin="round"></path><path stroke="#14142B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M53.093 36.349l3.256 4.558M71.908 36.349l-3.257 4.558M62 28v11"></path><defs><linearGradient id="paint0_linear" x1="15" y1="14" x2="175.329" y2="86.868" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient><linearGradient id="paint1_linear" x1="-17.798" y1="225.333" x2="189.003" y2="134.849" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset=".653" stop-color="#FDAD1F"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient></defs></svg>
                    <div class="text-center font-semibold md:text-lg">
                        Un service simple &amp; efficace
                    </div>
                </div>
                <div class="flex flex-col justify-start items-center gap-3">
                    <svg width="145" height="147" fill="none" xmlns="http://www.w3.org/2000/svg"><circle opacity=".2" cx="78.5" cy="80.5" r="66.5" fill="url(#paint0_linear)"></circle><circle cx="65" cy="65" r="63" stroke="url(#paint1_linear)" stroke-width="4"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M59.385 87c11.835 0 21-8.96 21-19.5S71.22 48 59.385 48s-21 8.96-21 19.5a18.2 18.2 0 001.219 6.563c.448 1.163.422 2.303.352 3.047-.077.808-.257 1.616-.45 2.332-.322 1.201-.794 2.549-1.208 3.728l-.205.585c-.175.502-.333.966-.471 1.392a74.9 74.9 0 001.255-.315c.16-.04.323-.084.491-.127 1.217-.317 2.645-.689 3.923-.904.742-.125 1.587-.226 2.434-.209.802.016 1.952.142 3.07.75C51.894 86.026 55.507 87 59.386 87zm0 4c13.807 0 25-10.521 25-23.5S73.192 44 59.385 44s-25 10.521-25 23.5c0 2.809.524 5.503 1.486 8 .458 1.19-.43 3.729-1.338 6.317-1.052 3.004-2.127 6.074-1.148 7.183 1 1.133 4.01.351 7-.426 2.652-.69 5.29-1.376 6.5-.718 3.677 2 7.946 3.144 12.5 3.144z" fill="#14142B"></path><circle cx="49.385" cy="68" r="3" fill="#14142B"></circle><circle cx="59.385" cy="68" r="3" fill="#14142B"></circle><circle cx="69.385" cy="68" r="3" fill="#14142B"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M70.166 45.393c-.487.625-1.842 3.236-1.534 4.265C72.5 50.377 78 55.5 78 55.5s2.813 7.39 3.683 12.08c.76.47 3.577-.142 4.25-.262a16.464 16.464 0 004.83-1.662c.885-.47 1.781-.582 2.404-.605.654-.024 1.293.04 1.844.123 1.048.158 2.2.45 3.145.69l.09.023.564.143-.261-.735-.03-.08c-.324-.905-.71-1.983-.962-2.96a9.959 9.959 0 01-.3-1.706c-.043-.563-.044-1.426.31-2.323.58-1.476.897-3.065.897-4.726 0-7.571-6.75-14.085-15.568-14.085-5.326 0-9.94 2.403-12.73 5.978zM82.448 67.58a18.625 18.625 0 01-.39 3.403 21.159 21.159 0 003.57-.162 20.058 20.058 0 006.792-2.162c.922-.49 2.932.02 4.953.534 2.279.579 4.571 1.161 5.334.318.746-.826-.074-3.113-.875-5.35-.691-1.927-1.369-3.817-1.02-4.704a16.196 16.196 0 001.133-5.957c0-9.665-8.529-17.5-19.05-17.5-6.865 0-12.883 3.337-16.235 8.342A16.864 16.864 0 0065 47.489c9.674 1.789 17.67 10.169 17.448 20.09z" fill="#14142B"></path><defs><linearGradient id="paint0_linear" x1="12" y1="14" x2="172.329" y2="86.868" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient><linearGradient id="paint1_linear" x1="-17.798" y1="225.333" x2="189.003" y2="134.849" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset=".653" stop-color="#FDAD1F"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient></defs></svg>
                    <div class="text-center font-semibold md:text-lg">
                        Des conseillers pour vous aider
                    </div>
                </div>
                <div class="flex flex-col justify-start items-center gap-3">
                    <svg width="145" height="147" fill="none" xmlns="http://www.w3.org/2000/svg"><circle opacity=".2" cx="78.5" cy="80.5" r="66.5" fill="url(#paint0_linear)"></circle><circle cx="65" cy="65" r="63" stroke="url(#paint1_linear)" stroke-width="4"></circle><g clip-path="url(#clip0)" fill="#14142B"><path d="M100.911 79.552c.026-.008.051-.018.076-.027.021-.008.043-.015.064-.024.024-.01.048-.022.071-.034l.063-.03c.022-.012.043-.026.065-.039.021-.013.042-.025.062-.039.021-.013.039-.029.059-.043.02-.016.041-.03.06-.046.021-.017.04-.036.06-.054.017-.016.034-.03.05-.047.03-.03.058-.062.085-.094l.015-.017.014-.02c.026-.033.051-.066.074-.101.013-.02.025-.042.037-.063.013-.022.027-.043.038-.065.013-.024.024-.049.035-.073.009-.02.02-.04.028-.062.01-.024.018-.05.027-.074l.023-.066c.007-.024.012-.049.018-.073l.017-.072c.005-.024.007-.049.011-.074.003-.024.008-.048.01-.073l.004-.087.002-.06c0-.052-.003-.102-.008-.152l-3.392-32.27a1.518 1.518 0 00-1.668-1.351L49.353 49.32l-.039.006a1.52 1.52 0 00-.631.234 1.503 1.503 0 00-.381.356l-.026.032-.012.02a1.516 1.516 0 00-.048.074l-.032.055a1.546 1.546 0 00-.038.074l-.029.061-.028.073c-.008.022-.017.044-.024.067-.008.023-.014.048-.02.072l-.018.07-.012.073-.011.075c-.003.023-.004.046-.005.07-.002.026-.004.053-.004.08l.001.066c.001.029.002.057.005.085l.001.025.979 9.31a1.518 1.518 0 103.02-.317l-.627-5.957 14.672 10.473a1.532 1.532 0 00-.183.188L54.03 79.297l-1.043-9.921a1.518 1.518 0 10-3.02.317l.195 1.845-7.8.82a1.518 1.518 0 10.317 3.019l7.8-.82.915 8.703a1.518 1.518 0 001.668 1.35l47.558-4.998c.049-.005.099-.013.148-.023l.067-.017c.026-.006.051-.012.076-.02zm-2.343-4.936L83.955 62.783l-.028-.02 9.054-9.48a1.518 1.518 0 00-2.195-2.096L75.314 67.383l-21.66-15.462L95.717 47.5l2.85 27.116zm-30.345-8.02c.097-.12.171-.248.226-.383l6.172 4.406a1.516 1.516 0 001.98-.187l5.242-5.487c.06.07.127.137.202.198l14.612 11.833-40.267 4.232 11.833-14.612z"></path><path d="M32.12 68.515l25.499-2.68a1.518 1.518 0 10-.318-3.02l-25.498 2.68a1.518 1.518 0 10.318 3.02zM33.889 59.918l11.449-1.204a1.518 1.518 0 10-.318-3.02l-11.449 1.204a1.518 1.518 0 10.318 3.02zM44.373 78.591l-11.071 1.164a1.518 1.518 0 10.317 3.02l11.072-1.164a1.518 1.518 0 10-.318-3.02z"></path></g><defs><linearGradient id="paint0_linear" x1="12" y1="14" x2="172.329" y2="86.868" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient><linearGradient id="paint1_linear" x1="-17.798" y1="225.333" x2="189.003" y2="134.849" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"></stop><stop offset=".653" stop-color="#FDAD1F"></stop><stop offset="1" stop-color="#FDC51D"></stop></linearGradient><clipPath id="clip0"><path fill="#fff" transform="rotate(-6 340.903 -240.437)" d="M0 0h70v70H0z"></path></clipPath></defs></svg>
                    <div class="text-center font-semibold md:text-lg">
                        Envoyez vos lettres à la Poste sans vous déplacer
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
