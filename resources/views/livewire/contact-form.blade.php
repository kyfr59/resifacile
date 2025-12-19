<form wire:submit.prevent="save" class="w-full grid grid-cols-1 md:grid-cols-2 gap-6 md:mt-12 p-6 rounded-xl shadow-xl">
    <div class="flex flex-col">
        <label
            for="first_name"
            class="mb-2 text-xs">Prénom</label>
        <input
            type="text"
            wire:model="first_name"
            placeholder="prénom"
            id="first_name"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('first_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error('first_name')
            <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label
            for="last_name"
            class="mb-2 text-xs">Nom</label>
        <input
            type="text"
            wire:model="last_name"
            placeholder="nom"
            id="last_name"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('last_name') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error('last_name')
            <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label
            for="email"
            class="mb-2 text-xs">Courriel</label>
        <input
            type="email"
            wire:model="email"
            placeholder="courriel"
            id="email"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error('email')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="flex flex-col">
        <label
            for="phone"
            class="mb-2 text-xs">Téléphone</label>
        <input
            type="text"
            wire:model="phone"
            placeholder="téléphone"
            id="phone"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300"
        />
    </div>
    <div class="col-span-1 md:col-span-2">
        <div class="flex flex-col select">
            <label
                for="object"
                class="mb-2 text-xs">Objet</label>
            <select
                wire:model="object"
                id="object"
                class="appearance-none w-full px-3 rounded-xl outline-none h-14 placeholder:text-blue-300 bg-gray-200 @error('object') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            >
                <option value="">sélectionner un objet</option>
                @foreach($objects as $object)
                    <option value="{{ $object }}">{{ $object }}</option>
                @endforeach
            </select>
        </div>
        @error('object')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-span-1 md:col-span-2 flex flex-col">
        <label
            for="message"
            class="text-xs mb-2">Message</label>
        <textarea
            wire:model="message"
            id="message"
            class="w-full border-2 p-3 rounded-xl outline-none h-[10rem] border-gray-300 @error('email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
            placeholder="votre message…"
        ></textarea>
        @error('message')
        <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    @if($success)
        <div class="rounded-[7px] bg-green-50 text-green-700 p-6 text-center col-span-2  border border-green-200">
            Votre message a bien été envoyé, nous vous répondre sous 48 heures.
        </div>
    @endif
    <div class="col-span-1 md:col-span-2 flex justify-end">
        <button class="w-auto bg-blue-700 text-white h-14 px-6 rounded-xl flex items-center justify-center" type="submit">Envoyer ma demande</button>
    </div>
</form>
