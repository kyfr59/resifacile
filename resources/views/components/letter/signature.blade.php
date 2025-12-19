<div class="md:col-start-2">
    <div class="md:text-lg"><span class="text-blue-700 text-xs">⬇</span>︎ <strong>Signez</strong> votre courrier <span class="text-blue-700 text-xs">⬇</span>︎</div>
    <div class="pb-3 text-sm text-blue-700 italic">Dessiner votre signature à l'intérieur du cadre ci-dessous</div>
    <div class="bg-white border-2 border-gray-300 rounded-xl overflow-hidden">
        <div
            wire:ignore
            x-data="signature(@entangle('signature'))"
            x-init="initSignature"
        >
            <div class="flex justify-end w-full bg-gray-50">
                <div
                    class="w-8 h-8 cursor-pointer flex items-center justify-center"
                    x-on:click="signaturePad.clear(); signature = null"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M13.5 22a9.75 9.75 0 1 0-9.75-9.75V13"/>
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m.75 9.997 3 3 3-3"/>
                    </svg>
                </div>
            </div>
            <div x-ref="signaturePad"></div>
        </div>
    </div>
</div>
