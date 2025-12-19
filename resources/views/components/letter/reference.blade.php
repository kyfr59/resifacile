<div class="col-span-1 md:col-span-2">
    <div class="flex flex-col w-full">
        <label for="object" class="flex items-center gap-3">
            <span class="hidden sm:inline flex-none font-semibold">N° de contrat ou d'adhérent. :</span>
            <input
                type="text"
                wire:model.defer="reference"
                placeholder="Numéro de contrat ou d'adhérent"
                id="reference"
                class="flex-auto w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
            />
        </label>
    </div>
</div>
