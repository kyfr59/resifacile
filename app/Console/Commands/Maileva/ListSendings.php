<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class ListSendings extends Command
{
    protected $signature = 'maileva:list-sendings';
    protected $description = 'List all sendings';
    private $baseUrl = 'https://api.sandbox.maileva.net/mail/v2';

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

                echo
                  $sending->status . ' - '
                . $sending->id . ' - '
                . $sending->documents_count . ' docs '
                . $sending->recipients_counts->total . ' recips '
                . PHP_EOL;

                if ($sending->documents_count > 0) {
                    $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}/documents");
                    $documents = json_decode($response->body());
                    foreach($documents->documents as $doc) {
                        echo "    - ".$doc->id . PHP_EOL;
                    }
                }
                $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}/recipients");
                $recipients = json_decode($response->body());
            }

        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
