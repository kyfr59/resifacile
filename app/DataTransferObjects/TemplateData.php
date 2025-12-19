<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class TemplateData extends Data
{
    /**
     * @param string $name
     * @param ModelData $model
     * @param string|null $letter
     */
    public function __construct(
        #[Required]
        public string $name,
        #[Required]
        public ModelData $model,
        public ?string $letter = null,
    ){
    }
}
