<form wire:submit.prevent="save" class="grid grid-cols-1">
    <div class="flex flex-col gap-6 md:gap-12">
        @foreach($recipients as $index => $recipient)
            <div class="grid grid-cols-1 md:grid-cols-2  gap-3 md:gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 col-span-1 md:col-span-2 gap-3 md:gap-6">
                    <label for="type_address" class="cursor-pointer flex border-2 border-gray-300 rounded-xl p-3 gap-3 items-center shadow-md shadow-gray-200/20">
                        <div class="flex-none flex justify-end">
                            <div class="flex items-center justify-end">
                                <div class="font-light cursor-pointer inline-flex items-center">
                                    <input type="checkbox" wire:model.live="typeAddress" id="type_address" value="receipt" class="toggle-checkbox"/>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 items-start justify-start md:justify-center flex-auto">
                            <div class="flex-auto leading-tight text-blue-700">Mon destinataire est <strong>un professionnel</strong></div>
                        </div>
                    </label>
                    @if(!$loop->first)
                        <div class="flex justify-end">
                            <button
                                wire:click.prevent="remove({{ $index }})"
                                class="w-8 h-14 md:h-8 text-blue-700 cursor-pointer flex items-center justify-center text-xs bg-white border border-b-[3px] border-gray-200 rounded-sm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14"
                                     class="w-4 h-4 stroke-current">
                                    <path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M.5 7h13"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                @if($recipient['type'] === \App\Enums\AddressType::PROFESSIONAL->value)
                    @include('components.forms.professional', ['person' => 'recipients', 'index' => $index])
                @else
                    @include('components.forms.personal', ['person' => 'recipients', 'index' => $index])
                @endif
                <div>
                    <div class="flex flex-col select">
                        <label
                            for="recipients_{{ $index }}_country"
                            class="mb-2 text-xs">Pays</label>
                        <select
                            wire:model.defer="recipients.{{ $index }}.country"
                            id="recipients_{{ $index }}_country"
                            class="appearance-none w-full px-3 rounded-xl outline-none h-14 placeholder:text-blue-300 bg-gray-200 @error('recipients.' . $index . '.country') outline outline-offset-2 outline-4 outline-red-100 @enderror"
                        >
                            <option value="null">Selectionnez le pays</option>
                            @foreach($default_countries as $country)
                                <option
                                    value="{{ Str::upper($country['name'].'_'.$country['code']) }}">{{ Str::upper($country['name']) }}</option>
                            @endforeach
                            <option disabled>───</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{ Str::upper($country['name'].'_'.$country['code']) }}">{{ Str::upper($country['name']) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('recipients.' . $index . '.country')
                    <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
    <div class="pt-6 flex flex-col">
        <div class="pb-6 flex flex-col-reverse sm:flex-row gap-6 justify-between">
            <a
                href="{{ url()->previous() }}"
                class="hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 text-gray-800 border border-gray-300 h-14 px-6 rounded-xl inline-flex items-center gap-6 justify-center">
                Retour
            </a>
            <button
                class="w-auto bg-blue-700 text-white h-14 px-6 rounded-xl flex items-center justify-center"
                type="submit"
            >Suivant
            </button>
        </div>
        @if($errors->any())
            <div class="p-6 text-white text-center w-full bg-red-500 rounded-lg">Nous avons observé des erreurs ou des oublis dans les informations fournies ci-dessus, veuillez vérifier vos informations.</div>
        @endif
    </div>
</form>
