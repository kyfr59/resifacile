<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PreviewDocumentController extends Controller
{
    public function __invoke(int $id, Request $request)
    {
        if (Auth::user()->id !== $id) {
            abort(401);
        }

        if(!$request->has('path')) {
            abort(404);
        }

        if(!$request->has('filename')) {
            abort(404);
        }

        return new Response(Storage::get($request->input('path')), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $request->input('filename') . '.pdf"',
        ]);
    }
}
