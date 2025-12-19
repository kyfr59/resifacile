<?php

namespace App\Actions\Letter;

use App\Contracts\Cart;
use App\DataTransferObjects\DocumentData;
use App\Enums\CampaignStatus;
use App\Enums\DocumentType;
use App\Models\Campaign;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class AddDocumentsAction
{
    /**
     * @var array
     */
    private array $documents = [];

    /**
     * @var Cart
     */
    private Cart $cart;

    public function __construct()
    {
        $this->cart = App::make(Cart::class);
    }

    /**
     * @param array $documents
     * @return void
     * @throws Exception
     */
    public function handle(array $documents): void
    {
        foreach($documents as $document) {
            $fileName = $document->getFilename();

            if($document->getClientOriginalExtension() === 'bin') {
                $fileName = explode('.', $fileName)[0] . '.' . last(explode('.', $document->getClientOriginalName()));
            }

            $path = Storage::putFileAs('campaigns/' . $this->cart->getId(), $document, $fileName);

            $segments = explode('/', $path);
            $document_name = array_pop($segments);

            $page = null;

            if(
                $document->getMimeType() === 'application/pdf' ||
                $document->getMimeType() === 'application/octet-stream'
            ) {
                $temp = Storage::disk('local')->putFileAs('temp/', $document, $fileName);

                $page = null;
                exec('/usr/bin/pdfinfo ' . $temp . ' | awk \'/Pages/ {print $2}\'', $page);

                Storage::disk('local')->delete($temp);
            }

            $this->documents[] = new DocumentData(
                file_name: $document_name,
                readable_file_name: $document->getClientOriginalName(),
                path: $path,
                size: $document->getSize(),
                type: match($document->getMimeType()) {
                    'application/pdf' => DocumentType::PDF,
                    'application/octet-stream' => DocumentType::PDF,
                    default => throw new Exception("{$document->getMimeType()} non pris en charge"),
                },
                number_of_pages: ($page) ? (int)$page[0] : 1,
            );
        }

        $this->cart->addDocuments($this->documents);
    }
}
