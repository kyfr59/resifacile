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

        $stream = DomPdf::loadView('templates.letter', [
            'recipient' => $recipientData->toArray(),
            'sender' => $senderData->toArray(),
            'letter' => $documentData->toArray()['letter'],
            'data' => $documentData->toArray()['model']['group_fields'],
        ])->stream();

        Storage::put(
            $path,
            $stream,
        );

        Storage::disk('local')->put($localPath, $stream);

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
}
