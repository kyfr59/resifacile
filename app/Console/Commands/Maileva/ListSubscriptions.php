<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class ListSubscriptions extends Command
{
    protected $signature = 'maileva:list-subscriptions';
    protected $description = 'List all subscriptions';
    private $sendingId = null;
    private $baseUrl = 'https://api.sandbox.maileva.net/notification_center/v2';

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->token = $token;
        $this->listSubscriptions();
    }

    private function listSubscriptions()
    {
        $response = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get($this->baseUrl . '/subscriptions');

        if ($response->successful()) {
            $json = $response->body();
            $response = json_decode($json);
            dd($response);

        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
