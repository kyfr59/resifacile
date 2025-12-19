<div class="flex flex-col items-center">
    <div class="relative bg-white shadow-md shadow-black/10 rounded-full w-28 aspect-square">
        <div class="absolute top-0 left-0 h-8 w-8 bg-blue-700 text-white flex items-center justify-center rounded-full">{{ $card->index }}</div>
        <div>{!! $card->picto !!}</div>
    </div>
    <h2 class="mt-3 text-center text-sm md:text-base text-gray-800 font-semibold leading-tight">{!! $card->label !!}</h2>
</div>
