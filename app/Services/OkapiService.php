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
            ->retry(2, 200) // 2 tentatives, 200ms entre chaque
            ->get("{$this->baseUrl}/idships/{$idShip}", [
                'lang' => $lang,
            ]);

        if ($response->failed()) {
            Log::warning('Erreur API Suivi La Poste', [
                'idShip' => $idShip,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new OkapiException(
                message: "Échec de l'appel Suivi pour {$idShip}",
                httpStatus: $response->status(),
                payload: $response->json(),
            );
        }

        return $response->json();
    }

    /**
     * Récupère uniquement le dernier événement connu, pratique pour le polling.
     */
    public function latestEvent(string $idShip): ?array
    {
        $data = $this->track($idShip);

        return $data['shipment']['event'][0] ?? null;
    }

    public function isFinal(string $idShip): bool
    {
        $data = $this->track($idShip);

        return (bool) ($data['shipment']['isFinal'] ?? false);
    }
}