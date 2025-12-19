<div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="flex flex-col">
        <label
            for="phone"
            class="text-xs text-blue-700"
        >Téléphone</label>
        <input
            type="text"
            wire:model.defer="phone"
            placeholder="Téléphone"
            id="phone"
            class="flex-auto w-full border px-3 rounded-[7px] outline-none h-8 placeholder:text-blue-300 border-blue-300"
        />
    </div>
    <div class="flex flex-col">
        <label
            for="email"
            class="text-xs text-blue-700"
        >Email</label>
        <input
            type="text"
            wire:model.defer="email"
            placeholder="Email"
            id="email"
            class="flex-auto w-full border px-3 rounded-[7px] outline-none h-8 placeholder:text-blue-300 border-blue-300"
        />
    </div>
</div>
