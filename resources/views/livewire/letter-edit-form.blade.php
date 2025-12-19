<form
    wire:submit.prevent="save"
    class="grid grid-cols-1"
>
    @if($product)
        <div class="w-full grid-cols-1 md:grid-cols-2 gap-3 md:gap-6 hidden sm:grid">
            <div>
                <div class="font-semibold">Visualisez ici l'adresse de votre destinataire</div>
                <div class="text-blue-700 italic text-sm pb-2">Vous pourrez modifier à l'étape suivante en cas erreur</div>
                <div class="bg-white pb-2 rounded-xl p-3 md:p-6 self-start font-[500] text-left text-sm md:text-base border-2 border-gray-300">
                    {{ $product->address->compagny }}<br/>
                    {{ $product->address->address_line_4 }}<br/>
                    {{ $product->address->postal_code }} {{ $product->address->city }}<br/>
                    {{ $product->address->country }}
                </div>
            </div>
        </div>
    @endif
    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
        <div class="col-span-1 md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                @include('components.letter.city-date')
                @include('components.letter.reference')
                @include('components.letter.object')
                <div class="col-span-1 md:col-span-2 md:row-auto">
                    <div class="rounded-[7px] @if($errors->has('template.model.text') || $errors->has('template.model.json')) outline outline-offset-2 outline-4 outline-red-100 @endif">
                        <div class="md:text-lg pb-1"><span class="text-blue-700 text-xs">⬇</span>︎ <strong class="font-semibold">Modifiez</strong> votre lettre de résiliation <span class="text-blue-700 text-xs">⬇</span>︎</div>
                        <div class="pb-3 text-sm text-blue-700 italic">Ne renseignez pas les adresses expéditeur et destinaire. Vous les renseignerez à une prochaine étape !</div>
                        <x-editor
                            wire:model="template.model"
                            wire:poll.10000ms="autosave"
                            class="bg-white outline-none border-2 border-gray-300 rounded-xl overflow-hidden"
                        ></x-editor>
                    </div>
                    @if($errors->has('template.model.text'))
                        <div class="text-sm text-red-500 pt-1">{{ $errors->first('template.model.text') }}</div>
                    @elseif($errors->has('template.model.json'))
                        <div class="text-sm text-red-500 pt-1">{{ $errors->first('template.model.json') }}</div>
                    @else
                        <div class="flex justify-start"><span class="mt-1 text-sm text-red-500 italic inline-flex px-2 bg-red-50">requis</span></div>
                    @endif
                </div>
                @include('components.letter.signature')
            </div>
        </div>
    </div>
    <div class="pt-6 md:pt-12 flex flex-col gap-6">
        <div class="flex gap-6 flex-col-reverse md:flex-row items-center justify-center md:justify-end">
            <div class="flex flex-col md:flex-row gap-6 w-full md:w-auto">
                <label for="importFiles" class="cursor-pointer flex border-2 border-gray-300 rounded-xl py-3 sm:py-0 px-3 gap-3 items-center shadow-md shadow-gray-200/20">
                    <div class="flex flex-col md:flex-row gap-1 items-start justify-start md:justify-center flex-auto">
                        <div class="flex-auto leading-tight text-base md:text-sm font-semibold">Avez vous des pièces jointes à fournir avec votre courrier ?</div>
                    </div>
                    <div class="flex-none flex justify-end">
                        <div class="flex items-center justify-end">
                            <div class="font-light cursor-pointer inline-flex items-center">
                                <input type="checkbox" id="importFiles" wire:model="importFiles" class="toggle-checkbox"/>
                            </div>
                        </div>
                    </div>
                </label>
                <button
                    class="hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 w-full md:w-auto bg-blue-700 text-white h-14 px-6 rounded-xl flex items-center justify-center"
                    type="submit">
                    Je poursuis
                </button>
            </div>
        </div>
    </div>
    @if($errors->any())
        <div class="mt-6 md:mt-12 p-6 text-white text-center w-full bg-red-500 rounded-lg">Nous avons observé des erreurs ou des oublis dans les informations fournies ci-dessus, veuillez vérifier vos informations.</div>
    @endif
</form>
