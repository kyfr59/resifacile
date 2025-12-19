<form
    class="flex flex-col-reverse md:flex-row gap-3 md:gap-6 items-center p-3 md:p-6 bg-red-50 rounded-[7px] justify-between"
    wire:submit.prevent="save"
>
    <div class="w-full md:w-auto bg-red-50 text-center border text-red-500 border-red-500 rounded-[7px] p-2 leading-tight text-sm md:text-base">Cela supprimera toutes vos données !</div>
    <button
        class="w-full md:w-auto bg-red-500 text-white h-14 md:h-10 px-4 rounded-[7px] flex items-center justify-center"
        type="submit"
    >Supprimer mon compte</button>
</form>
