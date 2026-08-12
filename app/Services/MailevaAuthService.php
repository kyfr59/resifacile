<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MailevaAuthService
{
    private string $connectionUrl;

    public function __construct()
    {
        $this->connectionUrl = config('maileva.connection_url');
    }

    public function getAccessToken(): string
    {
        Cache::forget('maileva_access_token');

        return Cache::remember('maileva_access_token', 3500, function () {
            $response = Http::asForm()->post(
                $this->connectionUrl . '/auth/realms/services/protocol/openid-connect/token',
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

            $token1 = $response->json()['access_token'];

            if (config('maileva.mode') == 'sandbox') {
                return $token1;
            }

            $response = Http::asForm()->post(
                $this->connectionUrl . '/auth/realms/services/protocol/openid-connect/token',
                [
                    'client_id' => config('maileva.client_id'),
                    'client_secret' => config('maileva.client_secret'),
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:token-exchange',
                    'subject_token' => $token1,
                    'subject_token_type' => 'urn:ietf:params:oauth:token-type:access_token',
                ]
            );

            if (! $response->successful()) {
                throw new \Exception('Maileva auth failed: '.$response->body());
            }

            $token2 = $response->json()['access_token'];

            $response = Http::asForm()->post(
                $this->connectionUrl . '/auth/realms/services/protocol/openid-connect/token',
                [
                    'client_id' => config('maileva.client_id'),
                    'client_secret' => config('maileva.client_secret'),
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:token-exchange',
                    'subject_token' => $token2,
                    'requested_token_type' => 'urn:ietf:params:oauth:token-type:access_token',
                    'requested_subject' => 'KOLIBRINETWORK.GANDILLON',
                ]
            );

            if (! $response->successful()) {
                throw new \Exception('Maileva auth failed: '.$response->body());
            }

            return $response->json()['access_token'];
        });
    }
}