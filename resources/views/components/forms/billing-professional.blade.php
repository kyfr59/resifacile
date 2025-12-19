<div class="col-span-1 md:col-span-2 flex flex-col">
    <label
        for="address_compagny"
        class="mb-2 text-xs text-blue-700 flex gap-2"
    >Raison social @error('address.compagny') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model="address.compagny"
        placeholder="ou dénomination"
        id="address_compagny"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.compagny') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.compagny')
        <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="flex flex-col">
    <label
        for="address_first_name"
        class="mb-2 text-xs text-blue-700 flex gap-2"
    >Prénom @error('address.first_name') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model="address.first_name"
        placeholder="prénom"
        id="address_first_name"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.first_name') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.first_name')
    <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="flex flex-col">
    <label
        for="address_last_name"
        class="mb-2 text-xs text-blue-700 flex gap-2"
    >Nom @error('address.last_name') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model="address.last_name"
        placeholder="nom"
        id="address_last_name"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.last_name') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.last_name')
    <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="col-span-1 md:col-span-2 flex flex-col">
    <label
        for="address_address_line_4"
        class="mb-2 text-xs text-blue-700 flex gap-2"
    >Adresse @error('address.address_line_4') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model.defer="address.address_line_4"
        placeholder="numéro et libellé de la voie"
        id="address_address_line_4"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.address_line_4') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.address_line_4')
        <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="col-span-1 md:col-span-2 flex flex-col" wire:key="{{ rand() }}" x-data="{showComplement: @entangle('showComplementAddress')}">
    <div class="text-xs text-blue-700">Complément d'adresse</div>
    <div class="flex flex-col w-full" x-show="showComplement" x-cloak>
        <label
            class="block"
            for="address_address_line_2"
        >
            <input
                type="text"
                wire:model.defer="address.address_line_2"
                placeholder="service ou identité du destinataire"
                id="address_address_line_2"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.address_line_2') border-red-500 @else border-blue-300 @enderror"
            />
        </label>
        @error('address.address_line_2')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
        @enderror
        <label
            class="block"
            for="address_address_line_3"
        >
            <input
                type="text"
                wire:model.defer="address.address_line_3"
                placeholder="entrée, bâtiment, immeuble, résidence ou ZI"
                id="address_address_line_3"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.address_line_3') border-red-500 @else border-blue-300 @enderror"
            />
        </label>
        @error('address.address_line_3')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
        @enderror
        <label
            class="block"
            for="address_address_line_5"
        >
            <input
                type="text"
                wire:model.defer="address.address_line_5"
                placeholder="boite postale, mention légale ou commune géographique"
                id="address_address_line_5"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.address_line_5') border-red-500 @else border-blue-300 @enderror"
            />
        </label>
        @error('address.address_line_5')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div x-show="!showComplement" class="pt-2">
        <div x-on:click.prevent="showComplement = true" class="relative pl-7 cursor-pointer w-full md:w-auto bg-blue-700 text-white h-6 px-6 rounded-full flex items-center justify-center gap-3 text-[.7rem] md:text-sm">
            <span class="absolute left-2 inline-flex items-center justify-center w-3 h-3 bg-white text-blue-700 font-semibold rounded-full">+</span> ajouter un complément d'adresse
        </div>
    </div>
</div>
<div class="flex flex-col">
    <label
        class="mb-2 text-xs text-blue-700 flex gap-2"
        for="address_postal_code"
    >Code postal @error('address.postal_code') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model.defer="address.postal_code"
        placeholder="code postal"
        id="address_postal_code"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.postal_code') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.postal_code')
        <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
<div class="flex flex-col">
    <label
        class="mb-2 text-xs text-blue-700 flex gap-2"
        for="address_city"
    >Localité @error('address.city') <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span> @else *@enderror</label>
    <input
        type="text"
        wire:model.defer="address.city"
        placeholder="localité"
        id="address_city"
        class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('address.city') border-red-500 @else border-blue-300 @enderror"
    />
    @error('address.city')
        <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
    @enderror
</div>
