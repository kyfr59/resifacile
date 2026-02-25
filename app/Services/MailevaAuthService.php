<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MailevaAuthService
{
    private string $baseUrl = 'https://connexion.sandbox.maileva.net';

    public function getAccessToken(): string
    {
        return Cache::remember('maileva_access_token', 3500, function () {
            $response = Http::asForm()->post(
                $this->baseUrl . '/auth/realms/services/protocol/openid-connect/token',
                [
                    'client_id' => config('maileva.client_id'),
                    'client_secret' => config('maileva.client_secret'),
                    'grant_type' => 'password',
                    'username' => config('maileva.username'),
                    'password' => config('maileva.password'),
                ]
            );

            if (! $response->successful()) {
                throw new \Exception('Maileva auth failed: '.$response->body());
            }

            return $response->json()['access_token'];
        });
    }
}