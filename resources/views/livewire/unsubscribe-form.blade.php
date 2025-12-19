<form wire:submit.prevent="save" class="grid grid-cols-1 gap-6 bg-white p-6 rounded-xl shadow-xl">
    <div class="flex flex-col">
        <label
            for="email"
            class="text-xs mb-2 text-gradient">Email de souscription</label>
        <input
            type="email"
            wire:model="email"
            placeholder="email"
            id="email"
            class="w-full border-2 px-3 rounded-xl outline-none h-14 border-gray-300 @error('email') outline outline-offset-2 outline-4 outline-red-100 @enderror"
        />
        @error('email')
            <div class="text-sm text-red-500 pt-1">{{ $message }}</div>
        @enderror
    </div>
    @if($success)
        <div class="rounded-[7px] bg-green-50 text-green-700 p-6 text-center border border-green-200">
            Un email vient de vous être envoyé contenant un lien de confirmation afin de finaliser votre demande.
        </div>
    @endif
    @if($error)
        <div class="rounded-[7px] bg-green-50 text-green-700 p-6 text-center border border-green-200">
            {{ $message }}
        </div>
    @endif
    @if(request()->session()->has('message'))
        <div class="rounded-[7px] bg-green-50 text-green-700 p-6 text-center col-span-2  border border-green-200">
            {{ request()->session()->get('message') }}
        </div>
    @endif
    <div class="flex justify-center sm:justify-end">
        <button
            class="w-auto bg-blue-700 text-white h-14 px-6 rounded-xl flex items-center justify-center"
            type="submit">
            Se désabonner
        </button>
    </div>
</form>
