<form
    wire:submit.prevent="save"
>
    <div class="col-span-1 md:col-span-2 font-semibold pb-3">Modifier les informations de profil</div>
    <div class="grid grid-cols-1 gap-3 md:gap-6 items-start">
        @if($customer->is_professional)
            <div class="flex flex-col gap-2">
                <label
                    for="compagny"
                    class="text-xs text-blue-700"
                >Raison sociale</label>
                <input
                    type="text"
                    placeholder="raison sociale"
                    id="compagny"
                    wire:model="customer.compagny"
                    class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-10 placeholder:text-blue-300 border-blue-300 @error('customer.compagny') outline outline-offset-2 outline-4 outline-red-100 @enderror"
                />
                @error('customer.compagny')
                    <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <div class="flex flex-col gap-2">
            <label
                for="first_name"
                class="text-xs text-blue-700"
            >Prénom</label>
            <input
                type="text"
                placeholder="prénom"
                id="first_name"
                wire:model="customer.first_name"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-10 placeholder:text-blue-300 border-blue-300 @error('customer.first_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            />
            @error('customer.first_name')
                <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label
                for="last_name"
                class="text-xs text-blue-700"
            >Nom</label>
            <input
                type="text"
                placeholder="nom"
                id="last_name"
                wire:model="customer.last_name"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-10 placeholder:text-blue-300 border-blue-300 @error('customer.last_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            />
            @error('customer.last_name')
                <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label
                for="email"
                class="text-xs text-blue-700"
            >Email</label>
            <input
                type="email"
                placeholder="email"
                id="email"
                wire:model="customer.email"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-10 placeholder:text-blue-300 border-blue-300 @error('customer.email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            />
            @error('customer.email')
                <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col col-span-1">
            <label
                for="phone"
                class="text-xs text-blue-700"
            >Téléhone</label>
            <input
                type="phone"
                placeholder="téléphone"
                id="phone"
                wire:model="customer.phone"
                class="w-full border px-3 rounded-[7px] outline-none h-14 md:h-10 placeholder:text-blue-300 @error('customer.phone') border-red-500 @else border-blue-300 @enderror"
            />
            @error('customer.phone')
            <div class="text-xs text-red-500 pt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col md:flex-row justify-end gap-6">
            @if (session()->has('message'))
                <div class="w-full md:w-auto bg-green-50 text-center rounded-[7px] text-green-700 p-2 leading-tight text-sm md:text-base">
                    {{ session('message') }}
                </div>
            @endif
            <button
                class="w-full md:w-auto bg-blue-700 text-white h-14 md:h-10 px-4 rounded-[7px] flex items-center justify-center"
                type="submit"
            >Mettre à jour le profil</button>
        </div>
    </div>
</form>
