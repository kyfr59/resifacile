<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="text-sm font-medium">
        @if($getState()?->address_lines->address_line_1)
            <div class="font-semibold">{{ $getState()->address_lines->address_line_1 }}</div>
        @endif
        @if($getState()?->address_lines->address_line_2)
            <div>{{ $getState()->address_lines->address_line_2 }}</div>
        @endif
        @if($getState()?->address_lines->address_line_3)
            <div>{{ $getState()->address_lines->address_line_3 }}</div>
        @endif
        @if($getState()?->address_lines->address_line_4)
            <div>{{ $getState()->address_lines->address_line_4 }}</div>
        @endif
        @if($getState()?->address_lines->address_line_5)
            <div>{{ $getState()->address_lines->address_line_5 }}</div>
        @endif
        @if($getState()?->address_lines->address_line_6)
            <div>{{ $getState()->address_lines->address_line_6 }}</div>
        @endif
        @if($getState()?->country)
            <div>{{ $getState()->country }}</div>
        @endif
    </div>
</x-dynamic-component>
