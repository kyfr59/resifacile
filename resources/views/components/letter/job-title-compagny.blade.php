<div class="md:col-start-2 grid grid-cols-2 gap-3">
    <div class="flex items-end w-full">
        <div class="flex flex-col flex-auto">
            <label
                for="job_title"
                class="text-xs text-blue-700"
            >Fonction</label>
            <input
                type="text"
                wire:model.defer="job_title"
                placeholder="Ex: Consultant"
                id="job_title"
                class="flex-auto w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 border-blue-300"
            />
        </div>
        <span class="flex-none">,</span>
    </div>
    <div class="flex flex-col">
        <label
            for="compagny"
            class="text-xs text-blue-700"
        >Raison social</label>
        <input
            type="text"
            wire:model.defer="compagny"
            placeholder="Ex: Acme SARL"
            id="compagny"
            class="flex-auto w-full border px-3 rounded-[7px] outline-none h-14 md:h-8 placeholder:text-blue-300 border-blue-300"
        />
    </div>
</div>
