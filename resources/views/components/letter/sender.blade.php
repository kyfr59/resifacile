@foreach($senders as $index => $sender)
    <div
        class="bg-white col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6 rounded-[14px] p-4 md:p-9 relative shadow-md shadow-gray-200/20">
        <div class="grid grid-cols-1 md:grid-cols-2 col-span-1 md:col-span-2 justify-between w-full">
            <label class="mb-2 flex flex-col select">
                <select
                    wire:model="senders.{{ $index }}.type"
                    id="senders_{{ $index }}_type"
                    class="appearance-none w-full px-3 border rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 bg-transparent border-blue-300"
                >
                    @foreach(\App\Enums\AddressType::cases() as $addressType)
                        <option
                            value="{{ $addressType }}" {{ ($addressType->value === $sender['type']) ? 'selected' : '' }}>{{ $addressType->label() }}</option>
                    @endforeach
                </select>
            </label>
            @if(!$loop->first)
                <div class="flex col-span-1 md:col-span-2 justify-end w-full absolute right-6 top-6">
                    <button
                        wire:click.prevent="remove({{ $index }})"
                        class="w-8 h-8 text-blue-700 cursor-pointer flex items-center justify-center text-xs bg-white border border-b-[3px] border-gray-300 rounded-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" class="w-4 h-4 stroke-current">
                            <path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M.5 7h13"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        @if($sender['type'] === \App\Enums\AddressType::PROFESSIONAL->value)
            @include('components.forms.professional', ['person' => 'senders', 'index' => $index])
        @else
            @include('components.forms.personal', ['person' => 'senders', 'index' => $index])
        @endif
        <div>
            <div class="flex flex-col select">
                <label
                    for="senders_{{ $index }}_country"
                    class="mb-2 text-xs text-blue-700 flex gap-2">Pays @error('senders.' . $index . '.country') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
                <select
                    wire:model.defer="senders.{{ $index }}.country"
                    id="senders_{{ $index }}_country"
                    class="appearance-none w-full px-3 border rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 bg-transparent @error('senders.' . $index . '.country') border-red-500 @else border-blue-300 @enderror"
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
            @error('senders.' . $index . '.country')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endforeach
