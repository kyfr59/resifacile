<?php

namespace App\Contracts;

use App\DataTransferObjects\AddressData;
use App\DataTransferObjects\DocumentData;
use App\Enums\InvoiceType;
use App\Models\Transaction;

interface Pdf
{
    /**
     * @param AddressData $recipientData
     * @param AddressData $senderData
     * @param DocumentData $documentData
     * @return DocumentData
     */
    public function make(AddressData $recipientData, AddressData $senderData, DocumentData $documentData): DocumentData;

    /**
     * @param Transaction $transaction
     * @param InvoiceType $type
     * @param string $invoiceNumber
     * @return string
     */
    public function makeInvoice(Transaction $transaction, InvoiceType $type, string $invoiceNumber): string;

    /**
     * @return void
     */
    public function makeAll(): void;
}
