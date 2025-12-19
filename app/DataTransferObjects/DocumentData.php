<?php

namespace App\DataTransferObjects;

use App\Enums\DocumentType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DocumentData extends Data
{
    /**
     * @param string $file_name
     * @param string|null $readable_file_name
     * @param string|null $path
     * @param int|null $size
     * @param ModelData|null $model
     * @param string|null $letter
     * @param DocumentType $type
     * @param int $number_of_pages
     */
    public function __construct(
        #[Required]
        public string $file_name,
        #[Nullable]
        public ?string $readable_file_name = null,
        #[Nullable]
        public ?string $path = null,
        #[Nullable,IntegerType]
        public ?int $size = null,
        #[Nullable]
        public ?ModelData $model = null,
        #[Nullable]
        public ?string $letter = null,
        #[Required]
        public DocumentType $type = DocumentType::PDF,
        #[Required,IntegerType]
        public int $number_of_pages = 1,
    ){
    }
}
