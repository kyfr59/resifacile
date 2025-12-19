<div class="pt-6 flex flex-col gap-3 border border-blue-700 rounded-[30px] p-6">
    <h3 class="h2 flex flex-col items-center gap-2">
        <div class="bg-amber-500 text-white rounded-full inline-flex items-center justify-center py-1 px-6 text-base">étape 1</div>
        <div class="text-center"><strong class="gradient">Je choisis</strong> ma méthode</div>
    </h3>
    <div class="grid grid-cols-1 gap-3">
        <div>
            <div class="text-center"><strong class="gradient">Envoyer des documents</strong></div>
            <div class="text-center text-red-500 text-sm">Format PDF uniquement, penser à convertir vos documents</div>
        </div>
        <div class="flex flex-col gap-6">
            <div>
                <div @if($errors->any())class="error"@endif><x-files wire:model="files" :documents="array_values(Arr::map($documents->filter(fn ($document) => $document->path !== null && $document->type !== \App\Enums\DocumentType::TEMPLATE && $document->type !== \App\Enums\DocumentType::HANDWRITE)->toArray(), fn ($document, $index) => [
                    'index' => $index,
                    'options' => [
                        'origin' => 'local',
                        'file' => [
                            'name' => $document['readable_file_name'],
                            'size' => $document['size'],
                        ],
                    ],
                ]))" /></div>
            </div>
        </div>
    </div>
    <div class="text-center font-semibold pt-3">OU</div>
    <div class="grid grid-cols-1 gap-3">
        <div class="text-center"><strong class="gradient">Rédiger mon courrier</strong> en ligne</div>
        <div class="flex flex-col portrait:flex-col sm:flex-row gap-3 portrait:gap-3 md:gap-6 w-full">
            <label for="color" class="cursor-pointer flex border border-gray-200 rounded-md p-3 w-full gap-3 items-center shadow-md shadow-gray-200/20">
                <div class="flex-auto leading-tight text-sm"><strong>Je rédige</strong> mon courrier</div>
                <div class="flex-none w-18 md:w-24 flex justify-end">
                    <div class="flex items-center justify-end">
                        <div class="font-light cursor-pointer inline-flex items-center">
                            <input type="checkbox" id="color" wire:model="handwrite" class="toggle-checkbox"/>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>
