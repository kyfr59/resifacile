<div
    class="flex flex-col-reverse md:flex-row gap-3 md:gap-6 items-center p-3 md:p-6 bg-red-50 rounded-[7px] justify-between"
    wire:submit.prevent="save"
>
    <div class="w-full md:w-auto bg-red-50 text-center border text-red-500 border-red-500 rounded-[7px] p-2 leading-tight text-sm md:text-base">Cela supprimera toutes vos données !</div>
        <div>
        <button wire:click="$set('showModal', true)" class="bg-red-600 text-white px-4 py-2 rounded">
            Supprimer mon compte
        </button>

        <div x-data="{ open: @entangle('showModal') }">
            <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-96">
                    <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                    <p class="mb-4">Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>

                    <input type="email" wire:model="email" placeholder="Entrez votre email" class="w-full mb-4 border rounded-[7px] px-3 py-2">

                    @error('email') <span class="text-red-500 text-sm block mb-5">{{ $message }}</span> @enderror

                    <div class="flex justify-end gap-3">
                        <button @click="open = false" class="px-4 py-2 bg-gray-300 rounded-[7px]">Annuler</button>
                        <button wire:click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-[7px]">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
