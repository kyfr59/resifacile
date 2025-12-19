<div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 top-0 max-w-screen-md w-full mx-auto px-6">
    <div class="bg-white flex items-center w-full shadow-2xl rounded-2xl overflow-hidden">
        <button x-on:click.prevent="$wire.set('openSearchBox', true)" x-on:keydown.meta.k.window="$wire.get('openSearchBox') ? $wire.set('openSearchBox', false) : $wire.set('openSearchBox', true)" class="border-2 border-blue-700 outline-none flex-1 flex leading-none items-center justify-between h-16 rounded-2xl px-6 md:text-xl gap-3">
            <div class="flex items-center gap-3 text-gray-500">
                <svg class="w-8 h-8" viewBox="0 0 19 21" fill="none">
                    <path class="stroke-blue-700" d="M14.9747 8C14.9747 11.8675 11.8449 15 7.98737 15C4.12984 15 1 11.8675 1 8C1 4.13252 4.12984 1 7.98737 1C11.8449 1 14.9747 4.13252 14.9747 8Z" stroke-width="2"/>
                    <line class="stroke-blue-700" x1="1" y1="-1" x2="8.21335" y2="-1" transform="matrix(0.650195 0.759767 -0.758746 0.651387 10.9827 14)" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Indiquez la marque à résilier
            </div>
            <kbd class="text-gray-300"><abbr title="Command">⌘k</abbr></kbd>
        </button>
    </div>
    @teleport('body')
        <div x-show="$wire.get('openSearchBox')" class="text-[#14142b] p-6 fixed flex justify-center items-center inset-0 backdrop-blur-md bg-gray-400/20 z-50">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-screen-md overflow-hidden">
                <div class="p-3 flex items-center border-b border-gray-300 overflow-hidden h-14">
                    <svg class="w-6 h-6 flex-none" viewBox="0 0 19 21" fill="none">
                        <path class="stroke-gray-400" d="M14.9747 8C14.9747 11.8675 11.8449 15 7.98737 15C4.12984 15 1 11.8675 1 8C1 4.13252 4.12984 1 7.98737 1C11.8449 1 14.9747 4.13252 14.9747 8Z" stroke-width="2"/>
                        <line class="stroke-gray-400" x1="1" y1="-1" x2="8.21335" y2="-1" transform="matrix(0.650195 0.759767 -0.758746 0.651387 10.9827 14)" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input wire:model.live.debounce.250ms="search" type="text" autocomplete="off" class="flex-1 outline-none h-14 px-3" placeholder="Indiquez la marque à résilier"/>
                </div>
                <div class="max-h-96 overflow-x-scroll pb-14">
                    <div class="pt-6 pb-3 px-6 font-semibold border-b border-gray-100">Les plus recherchés</div>
                    <div class="divide-y divide-gray-100">
                        @foreach($brands as $brand)
                            <a href="{{ route('frontend.template.edit', ['slug' => $brand->slug]) }}" class="hover:bg-gray-50 hover:text-blue-700 px-6 py-3 flex items-center justify-between w-full">
                                <div>{{ $brand->name }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endteleport
</div>
