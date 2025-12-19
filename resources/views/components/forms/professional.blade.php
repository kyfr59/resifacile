<div class="col-span-1 md:col-span-2 flex flex-col">
    <label
        for="{{ $person }}_{{ $index }}_compagny"
        class="mb-2 text-xs flex gap-2"
    >Raison social <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
    <input
        type="text"
        wire:model="{{ $person }}.{{ $index }}.compagny"
        placeholder="ou dénomination"
        id="{{ $person }}_{{ $index }}_compagny"
        class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.compagny') outline outline-offset-2 outline-4 outline-red-100 @enderror"
    />
    @error($person . '.' . $index . '.compagny')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
@if($person === 'senders')
    <div class="flex flex-col">
        <label
            for="{{ $person }}_{{ $index }}_first_name"
            class="mb-2 text-xs text-blue-700 flex gap-2"
        >Prénom <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
        <input
            type="text"
            wire:model="{{ $person }}.{{ $index }}.first_name"
            placeholder="prénom"
            id="{{ $person }}_{{ $index }}_first_name"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.first_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error($person . '.' . $index . '.first_name')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label
            for="{{ $person }}_{{ $index }}_last_name"
            class="mb-2 text-xs flex gap-2"
        >Nom <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
        <input
            type="text"
            wire:model="{{ $person }}.{{ $index }}.last_name"
            placeholder="nom"
            id="{{ $person }}_{{ $index }}_last_name"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.last_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error($person . '.' . $index . '.last_name')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label
            for="email"
            class="mb-2 text-xs flex gap-2"
        >Email <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
        <input
            type="email"
            wire:model="email"
            placeholder="email"
            id="email"
            class="bg-white w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error('email')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
@endif
<div class="col-span-1 md:col-span-2 flex flex-col">
    <label
        for="{{ $person }}_{{ $index }}_address_line_4"
        class="mb-2 text-xs flex gap-2"
    >Adresse <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
    <input
        type="text"
        wire:model.defer="{{ $person }}.{{ $index }}.address_line_4"
        placeholder="numéro et libellé de la voie"
        id="{{ $person }}_{{ $index }}_address_line_4"
        class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.address_line_4') outline outline-offset-2 outline-4 outline-red-100 @enderror"
    />
    @error($person . '.' . $index . '.address_line_4')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="col-span-1 md:col-span-2 flex flex-col" wire:key="{{ rand() }}">
    <div class="text-xs mb-2">Complément d'adresse</div>
    <div class="flex flex-col w-full gap-3">
        <label
            class="block"
            for="{{ $person }}_{{ $index }}_address_line_2"
        >
            <input
                type="text"
                wire:model.defer="{{ $person }}.{{ $index }}.address_line_2"
                placeholder="service ou identité du destinataire"
                id="{{ $person }}_{{ $index }}_address_line_2"
                class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
            />
        </label>
        <label
            class="block"
            for="{{ $person }}_{{ $index }}_address_line_3"
        >
            <input
                type="text"
                wire:model.defer="{{ $person }}.{{ $index }}.address_line_3"
                placeholder="entrée, bâtiment, immeuble, résidence ou ZI"
                id="{{ $person }}_{{ $index }}_address_line_3"
                class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
            />
        </label>
        <label
            class="block"
            for="{{ $person }}_{{ $index }}_address_line_5"
        >
            <input
                type="text"
                wire:model.defer="{{ $person }}.{{ $index }}.address_line_5"
                placeholder="boite postale, mention légale ou commune géographique"
                id="{{ $person }}_{{ $index }}_address_line_5"
                class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
            />
        </label>
    </div>
</div>
<div class="flex flex-col">
    <label
        class="mb-2 text-xs flex gap-2"
        for="{{ $person }}_{{ $index }}_postal_code"
    >Code postal <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
    <input
        type="text"
        wire:model.defer="{{ $person }}.{{ $index }}.postal_code"
        placeholder="code postal"
        id="{{ $person }}_{{ $index }}_postal_code"
        class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.postal_code') outline outline-offset-2 outline-4 outline-red-100 @enderror"
    />
    @error($person . '.' . $index . '.postal_code')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="flex flex-col">
    <label
        class="mb-2 text-xs flex gap-2"
        for="{{ $person }}_{{ $index }}_city"
    >Localité <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
    <input
        type="text"
        wire:model.defer="{{ $person }}.{{ $index }}.city"
        placeholder="localité"
        id="{{ $person }}_{{ $index }}_city"
        class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error($person . '.' . $index . '.city') outline outline-offset-2 outline-4 outline-red-100 @enderror"
    />
    @error($person . '.' . $index . '.city')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
