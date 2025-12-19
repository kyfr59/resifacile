<?php

namespace App\Actions\Letter;

use App\Contracts\Cart;
use App\DataTransferObjects\DocumentData;
use App\DataTransferObjects\ModelData;
use App\Enums\DocumentType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class AddTemplateAction
{
    /**
     * @var Cart
     */
    private Cart $cart;

    public function __construct()
    {
        $this->cart = App::make(Cart::class);
    }

    /**
     * @param array $template
     */
    public function __invoke(array $template): void
    {
        $documents = $this->cart->getDocuments();

        $documents->filter(
            fn ($document) => $document->type === DocumentType::TEMPLATE || $document->type === DocumentType::HANDWRITE
        )->each(function($document, $index) {
            (new RemoveDocumentAction())->handle($index);
        });

        $this->cart->addDocuments([new DocumentData(
            file_name: Str::uuid() . '.pdf',
            readable_file_name: Str::slug($template['group_fields']['object']) . '.pdf',
            model: ModelData::fromTemplate($template),
            letter: $template['model']['text'] ?? $template['letter'],
            type: ($template['document_type'] === DocumentType::TEMPLATE->value) ? DocumentType::TEMPLATE : DocumentType::HANDWRITE,
        )]);
    }
}
