<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TrackingController extends Controller
{
    public function __invoke(?string $tracking_number = null): View
    {
        return view('pages.tracking', [
            'trackingNumber' => $tracking_number,
        ]);
    }
}