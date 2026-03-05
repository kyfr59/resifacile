<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class Notifications extends Command
{
    protected $signature = 'maileva:notifications';
    protected $description = 'List all notifications';
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
        $this->listNotifications();
    }

    private function listNotifications()
    {
        $response = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get($this->baseUrl . '/notifications');

        if ($response->successful()) {
            $this->info('✅ Appel API test réussi');
            $json = $response->body();
            $response = json_decode($json);
            dd($response);
            foreach ($response->sendings as $sending) {
                echo $sending->id .' - '.$sending->documents_count .' docs '. PHP_EOL;
            }

        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
