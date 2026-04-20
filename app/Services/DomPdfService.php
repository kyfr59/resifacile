<?php

namespace App\Services;

use App\Contracts\Cart;
use App\Contracts\Pdf;
use App\DataTransferObjects\AddressData;
use App\DataTransferObjects\DocumentData;
use App\Enums\DocumentType;
use App\Enums\InvoiceType;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Storage;

class DomPdfService implements Pdf
{
    private mixed $cart;

    public function __construct()
    {
        $this->cart = App::make(Cart::class);
    }

    /**
     * @inheritDoc
     */
    public function make(
        AddressData $recipientData,
        AddressData $senderData,
        DocumentData $documentData
    ): DocumentData
    {
        $path = 'documents/' . $documentData->file_name;
        $localPath = 'temp/' . $documentData->file_name;

        $data = $documentData->toArray()['model']['group_fields'];
        $data['clean_city'] = $this->fixArticle("À ".$data['from_city']);

        $stream = DomPdf::loadView('templates.letter', [
            'recipient' => $recipientData->toArray(),
            'sender' => $senderData->toArray(),
            'letter' => $documentData->toArray()['letter'],
            'data' => $data,
        ])->stream();


        Storage::put(
            $path,
            $stream->getContent(),
        );

        Storage::disk('local')->put($localPath, $stream->getContent());

        $page = null;
        exec('/usr/bin/pdfinfo ' . storage_path('app/' . $localPath) . ' | awk \'/Pages/ {print $2}\'', $page);

        Storage::disk('local')->delete($localPath);

        $documentData->path = $path;
        $documentData->size = Storage::size($path);
        $documentData->number_of_pages = ($page) ? (int)$page : 1;

        return $documentData;
    }

    /**
     * @param Transaction $transaction
     * @param InvoiceType $type
     * @return string
     */
    public function makeInvoice(
        Transaction $transaction,
        InvoiceType $type,
        string $invoiceNumber
    ): string {
        $path = $type->relativePath() . '/' . $invoiceNumber . '.pdf';

        Storage::put(
            $path,
            DomPdf::loadView($type->template(), [
                'data' => $transaction,
                'number' => $invoiceNumber,
            ])->stream()
        );

        return $path;
    }

    /**
     * @inheritDoc
     */
    public function makeAll(): void
    {
        $document = $this->cart->getDocuments()
            ->first(
                fn ($document) => $document->type === DocumentType::TEMPLATE || $document->type === DocumentType::HANDWRITE
            );

        if($document) {
            $documents = $this->cart->getDocuments()->filter(
                fn ($document) => $document->type !== DocumentType::TEMPLATE && $document->type !== DocumentType::HANDWRITE
            )->toArray();

            $sender = $this->cart->getSenders()[0];

            foreach ($this->cart->getRecipients() as $recipient) {
                $documents[] = $this->make($recipient, $sender, $document);
            }

            $this->cart->updateAllDocuments($documents);
        }
    }

    private function fixArticle(string $text): string {
    // "à le" → "au"
    $text = preg_replace_callback(
        '/\b([Àà]) [Ll]e\b/u',
        fn($m) => ctype_upper($m[1]) ? 'Au' : 'au',
        $text
    );

    // "à les" → "aux"
    $text = preg_replace_callback(
        '/\b([Àà]) [Ll]es\b/u',
        fn($m) => ctype_upper($m[1]) ? 'Aux' : 'aux',
        $text
    );

    // "de le" → "du"
    $text = preg_replace_callback(
        '/\b([Dd])e [Ll]e\b/u',
        fn($m) => ctype_upper($m[1]) ? 'Du' : 'du',
        $text
    );

    // "de les" → "des"
    $text = preg_replace_callback(
        '/\b([Dd])e [Ll]es\b/u',
        fn($m) => ctype_upper($m[1]) ? 'Des' : 'des',
        $text
    );

    return ucfirst($text);
}
}
