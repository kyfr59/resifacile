<div class="col-span-1 md:col-span-2 flex flex-col md:flex-row gap-3">
    <div class="flex flex-col w-full">
        <label for="object" class="flex items-center gap-3 flex-auto w-full">
            <span class="hidden sm:inline flex-none font-semibold">Objet :</span>
            <input
                type="text"
                wire:model.defer="object"
                placeholder="Objet de votre courrier"
                id="object"
                class="flex-auto w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
            />
        </label>
    </div>
</div>
