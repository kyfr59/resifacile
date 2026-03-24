<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\MailevaAuthService;

class DeleteSendings extends Command
{
    protected $signature = 'maileva:delete-sendings';
    protected $description = 'Delete all sendings';
    private $baseUrl = 'https://api.sandbox.maileva.net/registered_mail/v4';

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
            $this->info('Connexion Maileva OK');

        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }
        $this->token = $token;
        $this->listSendings();
    }

    private function listSendings()
    {
        $response = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get($this->baseUrl . '/sendings');

       if ($response->successful()) {
            $this->info('✅ Appel API test réussi');
            $json = $response->body();
            $response = json_decode($json);
            foreach ($response->sendings as $sending) {

                $response = Http::withToken($this->token)
                    ->delete($this->baseUrl . "/sendings/{$sending->id}");
            }

        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
