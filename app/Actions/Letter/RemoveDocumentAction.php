<?php

namespace App\Actions\Letter;

use App\Contracts\Cart;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class RemoveDocumentAction
{
    /**
     * @param int $id
     * @return void
     */
    public function handle(int $id): void
    {
        $cart = App::make(Cart::class);

        $document = $cart->getDocument($id);

        $cart->removeDocument($id);

        if($document?->path) {
            Storage::delete($document->path);
        }
    }
}
