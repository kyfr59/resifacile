<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="text-sm flex gap-3">
        @foreach($getState() as $index => $document)
            @php
                $url = Storage::temporaryUrl($document->content->uri, now()->addMinutes(5));
            @endphp
            <a href="{{ $url }}" class="w-full flex items-center justify-center gap-2 py-2 px-3 border border-gray-200 rounded-md" target="_blank">
                {{ 'Courrier n° ' . ($index + 1) }} <span class="text-xs">({{ round($document->size / 1000) }} Ko)</span>
            </a>
        @endforeach
    </div>
</x-dynamic-component>
