@props(['name' => 'documents', 'documents' => []])
@inject('mimeTypes', 'App\Helpers\MimeTypes')
<label
    for="files"
    class="block w-full"
    wire:ignore
    x-init="() => {
        const filepond = FilePond.create(
            $refs.input,
            {
                files: {{ json_encode($documents,JSON_HEX_QUOT) }},
                acceptedFilesTypes: [{{ $mimeTypes::authorizedWithQuotes() }}],
                labelIdle: `Glissez & Déposez vos {{ $name }} <span class='underline'>ici</span> ou <span class='underline'>parcourez l'inspecteur de fichier</span>`,
                allowMultiple: true,
                allowPaste: false,
                allowReplace: false,
                allowProcess: false,
                labelFileTypeNotAllowed: 'Format de fichier non pris en charge',
                fileValidateTypeLabelExpectedTypes: '.pdf est autorisé',
                server: {
                    process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    }
                }
            }
        );

        document.addEventListener('FilePond:removefile', (e) => {
            @this.removeStoredDocument(e.detail.file.source)
        });

        document.addEventListener('FilePond:preparefile', (e) => {
        });
    }"
>
    <input type="file" name="files" id="files" multiple x-ref="input" accept="{{ $mimeTypes::authorized() }}" />
</label>
