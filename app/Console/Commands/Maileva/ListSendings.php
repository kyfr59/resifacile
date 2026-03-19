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
                . $sending->creation_date . ' - '
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

                // Détail d'un envoi
                if ($sending->id == 'd3472173-87e2-48b7-acde-71746e710b1e') {
                    continue;
                    $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}");
                    $response = json_decode($response->body());
                    dd($response);
                    $recipient_id = $response->recipients[0]->id;
                    $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}/recipients/{$recipient_id}");
                    $response = json_decode($response->body());
                    dd($response);
                    //dd(json_decode($response->body()));
                }
            }

        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
