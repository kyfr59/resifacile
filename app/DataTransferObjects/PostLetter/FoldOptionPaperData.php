<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class FoldOptionPaperData extends Data
{
    /**
     * @param DocumentOptionPaperData|null $document_option
     * @param String|null $envelope_window_type
     * @param String|null $envelope_type
     * @param String|null $postage_class
     * @param bool $fold_print_color
     * @param bool $print_sender_address
     * @param bool $use_fly_leaf
     */
    public function __construct(
        #[Nullable]
        public ?DocumentOptionPaperData $document_option = null,
        #[Nullable]
        public ?String $envelope_window_type = null,
        #[Nullable]
        public ?String $envelope_type = null,
        #[Nullable]
        public ?String $postage_class = null,
        #[BooleanType]
        public bool $fold_print_color = false,
        #[BooleanType]
        public bool $print_sender_address = false,
        #[BooleanType]
        // Ajout d’une page porte adresse, d'office sur C4
        // TODO: ne pas mettre les addresses sur le courrier
        // TODO: mettre l'option quand pas de template
        public bool $use_fly_leaf = false,
        #[BooleanType]
        // Gestion électronique des PND
        public bool $treat_undelivered_mail = false,
        #[BooleanType]
        // Gestion électronique des AR
        public bool $treat_ar = false,
    ){
    }
}
