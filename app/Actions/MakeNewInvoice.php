<?php

namespace App\Actions;

use App\Contracts\Pdf;
use App\Enums\InvoiceType;
use App\Helpers\Accounting;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Settings\AccountingSettings;
use Illuminate\Support\Facades\App;

class MakeNewInvoice
{
    /**
     * @param Transaction $transaction
     * @param InvoiceType $type
     * @return Invoice
     */
    public function handle(Transaction $transaction, InvoiceType $type): Invoice
    {
        $accountingSettings = App::make(AccountingSettings::class);

        if($type === InvoiceType::ORDER) {
            ++$accountingSettings->invoice_number;

            $invoiceNumber = Accounting::makeNumber(
                prefix: $accountingSettings->invoice_prefix,
                number: $accountingSettings->invoice_number,
            );
        } else {
            ++$accountingSettings->subscription_number;

            $invoiceNumber = Accounting::makeNumber(
                prefix: $accountingSettings->subscription_prefix,
                number: $accountingSettings->subscription_number,
            );
        }

        $path = App::make(Pdf::class)->makeInvoice(
            transaction: $transaction,
            type: $type,
            invoiceNumber: $invoiceNumber,
        );

        $invoice = new Invoice();
        $invoice->number = $invoiceNumber;
        $invoice->path = $path;
        $invoice->type = $type;
        $invoice->transaction()->associate($transaction);
        $invoice->save();

        $accountingSettings->save();

        return $invoice;
    }
}
