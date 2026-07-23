<?php

namespace App\Services;

use App\Exceptions\OkapiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OkapiService
{
    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $baseUrl,
    ) {}

    /**
     * Récupère le suivi d'un envoi.
     *
     * @throws OkapiException
     */
    public function track(string $idShip, string $lang = 'fr_FR'): array
    {
        $response = Http::withHeaders([
                'X-Okapi-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->timeout(10)
            ->get("{$this->baseUrl}/idships/{$idShip}", [
                'lang' => $lang,
            ]);

        if ($response->json('returnCode') != 200 || $response->failed()) {
            return [
                'success' => false,
                'message' => $response->json('returnMessage') ?? 'Numéro de suivi invalide',
                'events' => [],
            ];
        }

        $okapiStatus = $response->json('shipment.timeline');
        $steps = [
            [
                'number' => 1,
                'word' => 'Prise en charge',
                'label' => 'Votre envoi est pris en charge par La Poste',
                'description' => 'Votre courrier a été confié à La Poste. Il va maintenant être préparé et intégré au réseau postal.',
                'status' => $okapiStatus[0]['status'],
                'state' => $okapiStatus[0]['status'] == true ? 'done' : 'active',
            ],
            [
                'number' => 2,
                'word' => 'Préparation',
                'label' => 'Votre envoi est trié sur la plateforme de départ.',
                'description' => 'Votre courrier est en cours de tri sur la plateforme de départ avant de poursuivre son acheminement.',
                'status' => $okapiStatus[1]['status'],
                'state' => $okapiStatus[1]['status'] == true
                        ? 'done'
                        : ($okapiStatus[0]['status'] == true ? 'active' : 'pending'),
                ],
            [
                'number' => 3,
                'word' => 'En cours d\'acheminement',
                'label' => 'Il est en cours de transport vers son site de distribution.',
                'description' => 'Votre courrier est en cours d\'acheminement vers le site de distribution chargé d’assurer sa distribution au destinataire.',
                'status' => $okapiStatus[2]['status'],
                'state' => $okapiStatus[2]['status'] == true
                    ? 'done'
                    : ($okapiStatus[1]['status'] == true ? 'active' : 'pending'),
                ],
            [
                'number' => 4,
                'word' => 'En cours de distribution',
                'label' => 'Votre envoi est en préparation pour sa distribution.',
                'description' => 'Votre courrier est arrivé sur son site de distribution. Il est maintenant préparé pour être remis au destinataire par le facteur.',
                'status' => $okapiStatus[3]['status'],
                'state' => $okapiStatus[3]['status'] == true
                    ? 'done'
                    : ($okapiStatus[2]['status'] == true ? 'active' : 'pending'),
                ],
            [
                'number' => 5,
                'word' => 'Distribué',
                'label' => 'Votre envoi a été distribué.',
                'description' => 'Votre courrier a été remis à son destinataire conformément aux informations de suivi transmises par La Poste.',
                'status' => $okapiStatus[4]['status'],
                'state' => $okapiStatus[4]['status'] == true
                    ? 'done'
                    : ($okapiStatus[3]['status'] == true ? 'active' : 'pending'),
                ],
        ];

        $lastTrueStep = null;

        foreach ($steps as $step) {
            if ($step['status'] == 'true') {
                $lastTrueStep = $step;
            }
        }

        $lastStatusLabel = $lastTrueStep['label'] ?? null;
        $lastStatusDescription = $lastTrueStep['description'] ?? null;

        return [
            'success' => true,
            'events' => $response->json('shipment.event', []),
            'steps' => $steps,
            'lastStatusLabel' => $lastStatusLabel,
            'lastStatusDescription' => $lastStatusDescription,
            'isFinal' => $response->json('shipment.isFinal')
        ];
    }

    /**
     * Récupère uniquement le dernier événement connu, pratique pour le polling.
     */
    public function latestEvent(string $idShip): ?array
    {
        $data = $this->track($idShip);

        return $data['events'][0] ?? null;
    }
}