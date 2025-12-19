<?php

namespace App\Actions\Subscription;

use App\Enums\DocumentType;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Order;
use App\Models\Page;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class CreateServiceAgreementAction
{
    public function __invoke(Order $order): Document
    {
        $activities = Activity::causedBy($order->customer)->forSubject($order)->get();
        $uuid = Str::uuid();

        $path = 'services-agreements/' . $uuid . '.pdf';

        Storage::put(
            $path,
            DomPdf::loadView('templates.service-agreement', [
                'activities' => $activities,
                'uuid' => $uuid,
                'page' => Page::where('slug', 'cgv')->first(),
            ])->stream()
        );

        $document = new Document();
        $document->readable_file_name = 'conditions-generales-de-vente.pdf';
        $document->file_name = $uuid . '.pdf';
        $document->path = $path;
        $document->size = Storage::size($path);
        $document->type = DocumentType::SERVICE_AGREEMENT;
        $document->number_of_pages = 7;

        $document->order()->associate($order);
        $document->customer()->associate($order->customer);

        $document->save();

        return $document;
    }
}
