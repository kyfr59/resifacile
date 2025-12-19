<x-layouts.app>
    <x-slot:head>
        <title>{{ $page->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $page->seo_description }}"/>
        <meta name="robots" content="index, noarchive, nocache, imageindex"/>
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
                    "name": "{{ ($handle  === 'marques') ? 'Marques à résilier' : 'Lettre de résiliation' }}",
                    "item": "{{ ($handle  === 'marques') ? url('/trouvez-une-marque') : url('/categories') }}"
                },{
                    "@type": "ListItem",
                    "position": 1,
                    "item": "{{ url()->current() }}"
                }]
            }
        </script>
    </x-slot:head>
    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <section class="bg-gray-50">
            <div class="grid grid-cols-1 gap-3 md:gap-6 w-full max-w-screen-lg mx-auto py-12 md:py-24 px-6">
                <x-sale-process.file-arianne etape="remplir"/>
                <div class="sm:px-0">
                    <div class="text-xl md:text-2xl font-semibold">Personnaliser ma lettre</div>
                    <div class="text-2xl md:text-4xl text-gradient">
                        @unless($handle  === 'marques')
                            Modèle <strong>{{ Str::lower($page->name) }}</strong>
                        @else
                            Lettre de résiliation <strong>{{ Str::lower($product->name) }}</strong>
                        @endunless
                    </div>
                </div>
                <livewire:letter-edit-form
                    :template="$template"
                    :product="$product"
                    :object="$page->object ?? $page->template->object"
                />
            </div>
        </section>
    </div>
    <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex flex-col gap-6 md:gap-12">
        @if($page['article'])
            <article class="article w-full md:w-4/5">
                {!! Str::markdown($page['article']) !!}
            </article>
        @endif
        <div class="col-span-2">
            <div class="self-start overflow-hidden rounded-xl relative shadow-xl bg-white w-full p-6">
                <div class="font-semibold text-lg text-gradient pb-3">Modèles associés :</div>
                <div class="flex flex-col text-sm divide-y divide-gray-100">
                    @foreach($page->categories as $category)
                        @foreach($category->templates as $template)
                            @if($page->id !== $template->id)
                                <a href="{{ route('frontend.template.edit', $template) }}" class="py-1.5 hover:text-blue-700 flex items-center w-full justify-between">
                                    <span>{{ $template->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="flex-none h-3 stroke-current"><path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M3.85.5 10 6.65a.48.48 0 0 1 0 .7L3.85 13.5"></path></svg>
                                </a>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
