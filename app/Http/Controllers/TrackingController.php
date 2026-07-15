<?php

namespace App\Http\Controllers;

use App\Services\OkapiService;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function __construct(
        private readonly OkapiService $okapiService
    ) {
    }

    /**
     * Affichage de la page de suivi
     */
    public function __invoke(?string $tracking_number = null): View
    {
        $tracking = null;

        if ($tracking_number) {
            $tracking = $this->okapiService->track($tracking_number);
        }

        return view('pages.tracking', [
            'trackingNumber' => $tracking_number,
            'tracking' => $tracking,
        ]);
    }


    /**
     * Traitement du formulaire
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'tracking_number' => [
                'required',
                'string',
            ],
        ]);

        return redirect()->route('tracking', [
            'tracking_number' => $validated['tracking_number'],
        ]);
    }
}