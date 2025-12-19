<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class ModelData extends Data
{
    /**
     * @param array|string $model
     * @param array|null $group_fields
     * @param bool $is_new_type
     */
    public function __construct(
        #[Required]
        public array|string $model,
        public ?array $group_fields = null,
        #[Required]
        public bool $is_new_type = false,
    ){
    }

    /**
     * @param array $template
     * @return self
     */
    public static function fromTemplate(array $template): self
    {
        if(is_array($template['model']) && array_key_exists('json', $template['model'])) {
            return new self(
                model: $template['model']['json'],
                group_fields: $template['group_fields'],
                is_new_type: true,
            );
        }

        return new self(
            model: $template['model'],
            group_fields: $template['group_fields'],
        );
    }
}
