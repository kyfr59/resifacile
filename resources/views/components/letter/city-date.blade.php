<div class="md:col-start-2 gap-3 md:gap-0 grid grid-cols-1 md:grid-cols-2">
    <div class="flex flex-col">
        <label for="from_city" class="flex items-center gap-3">
            <span class="hidden sm:inline flex-none">À</span>
            <input
                type="text"
                wire:model.defer="from_city"
                placeholder="localité"
                id="from_city"
                class="flex-auto w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('from_city') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            />
        </label>
        @error('from_city')
            <div class="text-sm text-red-500 pt-1 text-right">{{ $message }}</div>
        @else
            <div class="flex justify-end"><span class="mt-1 text-xs text-red-500 italic inline-flex px-2 bg-red-50">requis</span></div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label for="date" class="flex items-center gap-3">
            <span class="hidden sm:inline flex-none"><i class="hidden md:inline">, </i>le</span>
            <input
                type="text"
                wire:model.defer="date"
                placeholder="date"
                id="date"
                class="flex-auto w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('date') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            />
        </label>
    </div>
</div>
