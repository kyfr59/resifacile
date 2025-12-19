<?php

namespace App\Http\Controllers\Account;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PreviewInvoiceController extends Controller
{
    public function __invoke(int $id)
    {
        $invoice = Invoice::find($id);

        if (Auth::user()->id !== $invoice->transaction->transactionable->customer->id) {
            abort(401);
        }

        return new Response(Storage::get($invoice->path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $invoice->number . '.pdf"',
        ]);
    }
}
