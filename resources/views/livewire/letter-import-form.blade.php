<div class="flex flex-col gap-6 sm:p-6 rounded-xl shadow-xl bg-white">
    <div class="w-full">
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
        @if($errors->any())
            <ul class="text-sm text-red-500 pt-1 w-full">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
        <a
            href="{{ back()->getTargetUrl() }}"
            class="hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 text-gray-800 border-2 border-gray-200 h-14 px-6 rounded-lg inline-flex items-center gap-6 justify-center">
            Retour
        </a>
        <button
            class="hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 w-auto bg-blue-700 text-white h-14 px-24 md:px-6 rounded-lg flex items-center justify-center"
            type="submit"
            wire:click.prevent="save"
        >
            Je poursuis
        </button>
    </div>
</div>
