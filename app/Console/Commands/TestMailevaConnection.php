<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class TestMailevaConnection extends Command
{
    protected $signature = 'maileva:test';
    protected $description = 'Test Maileva authentication';

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();

            $this->info('✅ Connexion Maileva OK');
            $this->line('Token généré :');
            $this->line(substr($token, 0, 50).'...');

        } catch (\Throwable $e) {
            $this->error('❌ Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->testApiEndpoint($token);
    }

    private function testApiEndpoint($token)
    {
        $baseUrl = 'https://api.sandbox.maileva.net/mail/v2';

        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get($baseUrl . '/sendings');

        if ($response->successful()) {
            $this->info('✅ Appel API test réussi');
            $this->line('Status: ' . $response->status());
            $this->line('Response: ' . json_encode($response->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());

            $this->line('Headers envoyés:');
            $this->line('- Authorization: Bearer [token caché]');
            $this->line('- Accept: application/json');
        }
    }
}
