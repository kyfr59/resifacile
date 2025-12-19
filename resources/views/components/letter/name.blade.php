<div class="md:col-start-2">
    <div class="flex flex-col">
        <label
            for="name"
            class="mb-2 text-xs text-blue-700 flex gap-2"
        >Civilité, prénom et/ou nom <span class="text-red-500 italic inline-flex px-2 bg-red-50">requis</span></label>
        <input
            type="text"
            wire:model.defer="name"
            placeholder="Ex: Monsieur Jean Dupond"
            id="name"
            class="flex-auto w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 @error('name') border-red-500 @else border-blue-300 @enderror"
        />
        @error('name')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
