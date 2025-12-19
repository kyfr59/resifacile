<?php

namespace App\Http\Controllers\SaleProcess;

use App\Contracts\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PreviewController extends Controller
{
    public function __invoke(Cart $cart, ?int $id = null)
    {
        if(is_null($id)) {
            abort(401);
        }

        $document = $cart->getDocuments()->toArray()[$id];

        return new Response(Storage::get($document['path']), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'inline; filename="' . $document['file_name'] . '"',
        ]);
    }
}
