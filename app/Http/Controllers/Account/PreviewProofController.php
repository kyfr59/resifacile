<?php

namespace App\Http\Controllers\Account;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Sending;

class PreviewProofController extends Controller
{
    public function __invoke(int $id, Request $request)
    {
        if (Auth::user()->id !== $id) {
            abort(401);
        }

        if(!$request->has('sending_id')) {
            abort(404);
        }

        if(!$request->has('filename')) {
            abort(404);
        }

        $sending = Sending::find($request->get('sending_id'));
        $mailevaId = $sending->maileva['sending_id'];

        $path = "proofs-of-deposit/{$mailevaId}.pdf";

        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        return response()->file(storage_path('app/' . $path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $request->input('filename') . '.pdf"',
        ]);
    }
}
