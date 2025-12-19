<div
    x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value }}'))"
    x-init="init($refs.editor)"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <div class="flex justify-center w-full bg-gray-50">
        <div
            class="w-8 h-8 cursor-pointer flex items-center justify-center text-xs"
            x-on:click.prevent="Alpine.raw(editor).chain().focus().toggleBold().run()"
        >
            <span class="font-semibold text-base">B</span>
        </div>
        <div
            class="w-8 h-8 cursor-pointer flex items-center justify-center text-xs"
            x-on:click.prevent="Alpine.raw(editor).chain().focus().toggleItalic().run()"
        >
            <span class="italic text-base">I</span>
        </div>
        <div
            class="w-8 h-8 cursor-pointer flex items-center justify-center text-xs"
            x-on:click.prevent="Alpine.raw(editor).chain().focus().toggleUnderline().run()"
        >
            <span class="underline text-base">U</span>
        </div>
    </div>
    <div x-ref="editor" id="editor"></div>
</div>
