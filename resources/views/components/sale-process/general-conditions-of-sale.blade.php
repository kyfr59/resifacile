<div
    class="fixed z-50 inset-0 p-0 sm:p-6 flex justify-center items-start bg-blue-700 bg-opacity-75"
    x-show="showPopup"
    x-cloak
>
    <div class="bg-white p-2 md:p-4 sm:shadow-xl w-full max-w-4xl relative overflow-hidden sm:rounded-[14px]">
        <button class="w-6 h-6 absolute top-4 right-4 cursor-pointer"
                x-on:click.prevent="showPopup = false"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="w-6 h-6 text-blue-700">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m13.5.5-13 13M.5.5l13 13"/>
                </g>
            </svg>
        </button>
        <div
            class="absolute bottom-0 left-0 right-0 bg-white pt-12 px-12 pb-44 sm:pb-12 flex items-center justify-center bg-opacity-75"
            x-ref="action"
            x-show="show"
        >
            <div
                class="cursor-pointer bg-blue-700 text-white w-16 h-16 rounded-full flex items-center justify-center"
                x-on:click.prevent="() => {
                    $refs.validation.scrollIntoView({behavior: 'smooth'}, true);
                }"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="mt-1 w-8 h-8 fill-current"><path d="M.5,3.85,6.65,10a.48.48,0,0,0,.7,0L13.5,3.85" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
        </div>
        <article class="article-gradient p-4 md:p-8 overflow-y-auto h-screen sm:max-h-[600px] text-small">
            {{--
            <div class="bg-amber-50 text-amber-700 p-3 text-center border border-amber-200 rounded-[7px] mb-6 flex flex-col md:flex-row gap-3 justify-between items-center">
                Afin de confirmer et régler votre commande, merci de valider les CGV
                <a href="{{ url('cgv-cgu.pdf') }}" class="w-auto border border-amber-200 text-amber-700 h-8 px-6 rounded-full inline-flex gap-2 items-center justify-center text-xs" target="_blank">Télécharger</a>
            </div>
            --}}
            <div class="h1 text-center gradient pt-6">{{ $page->title }}</div>
            {!! Str::markdown($page->article) !!}
            <div
                class="flex flex-col gap-6 mt-12 border-t border-gray-200 py-6 sm:p-6"
                x-ref="validation"
                x-intersect="show = false"
                x-transition
            >
                <div>
                    <label for="cgv" class="text-sm border rounded-md p-3 gap-3 flex flex-row items-center justify-center md:justify-between border-gray-200 shadow-md shadow-gray-200/20 @error('customerCertifiesHavingReadTheGeneralConditionsOfSale') outline outline-offset-2 outline-4 outline-red-100 @enderror">
                        <div class="flex-auto leading-tight text-left">J'ai lu les conditions générales de vente et j'y adhère sans réserve</div>
                        <div class="flex-none w-18 flex justify-center md:justify-end">
                            <div class="flex items-center justify-center md:justify-end">
                                <div class="font-light cursor-pointer inline-flex items-center">
                                    <input wire:model="customerCertifiesHavingReadTheGeneralConditionsOfSale" type="checkbox" id="cgv" value="true" class="toggle-checkbox">
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div>
                    <label for="start-service" class="text-sm border rounded-md p-3 gap-3 flex flex-row items-center justify-center md:justify-between border-gray-200 shadow-md shadow-gray-200/20 @error('expresslyRequestsTheStartOfTheService') outline outline-offset-2 outline-4 outline-red-100 @enderror">
                        <div class="flex-auto leading-tight text-left">Je demande expressément l'exécution de ma commande avant la fin du délai de rétraction</div>
                        <div class="flex-none w-18 flex justify-center md:justify-end">
                            <div class="flex items-center justify-center md:justify-end">
                                <div class="font-light cursor-pointer inline-flex items-center">
                                    <input wire:model="expresslyRequestsTheStartOfTheService" type="checkbox" id="start-service" value="true" class="toggle-checkbox">
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="w-full md:w-auto bg-blue-700 text-white py-3 md:py-0 h-14 md:h-12 px-6 rounded-[7px] flex gap-2 items-center justify-center text-base md:text-lg leading-tight"
                    >
                        J'y adhère et règle ma commande
                    </button>
                </div>
            </div>
        </article>
    </div>
</div>
