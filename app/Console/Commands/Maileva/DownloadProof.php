<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\MailevaAuthService;

class DownloadProof extends Command
{
    protected $signature = 'maileva:download-proof';
    protected $description = 'Upload and store a PDF proof document';
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

        $lastProcessedSendingIds = $this->getLastProcessedSendingWithRecipient();
        if (!$lastProcessedSendingIds) {
            $this->error('Aucun envoi avec un destinataire et un statut PROCESSED');
            die();
        }
        $sending_id = $lastProcessedSendingIds[0];
        $recipient_id = $lastProcessedSendingIds[1];

        // Preuve du contenu du destinataire
        // @see https://www.maileva.com/catalogue-api/envoi-et-suivi-de-maileva-lrel/
        $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending_id}/recipients/{$recipient_id}/content_proof/download_embedded_document");
        Storage::put("documents/preuve-{$sending_id}.pdf", $response->body());
    }

    private function getLastProcessedSendingWithRecipient()
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

                if ($sending->status == 'PROCESSED' && $sending->recipients_counts->total > 0) {
                    $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}/recipients");
                    $response = json_decode($response->body());
                    $recipient_id = $response->recipients[0]->id;
                    if (!empty($recipient_id)) {
                        return [$sending->id, $recipient_id];
                    }
                    return null;
                }
            }
        } else {
            $this->error('❌ Échec de l\'appel API test');
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
