<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\MailevaAuthService;

class DownloadProof extends Command
{
    protected $signature = 'maileva:download-proof {sending_id}';
    protected $description = 'Download a PDF proof document and store it on current dir';
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

        $this->sendingId = $this->argument('sending_id');

        $sending = $this->getSending();

        $recipient = $this->getRecipient($sending);

        $proof = $this->getProof($sending, $recipient);

        file_put_contents("./preuve-{$sending->id}.pdf", $proof);
    }

    private function getSending()
    {
        $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$this->sendingId}");
        $sending = json_decode($response->body());
        if ($sending->status != 'PROCESSED') {
            throw new \Exception("L'envoi n'a pas le statut PROCESSED");
        }
        return $sending;
    }


    private function getRecipient($sending)
    {
        $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sending->id}/recipients");
        $recipient = json_decode($response->body());
        return $recipient->recipients[0];
    }

    // Preuve du contenu du destinataire
    // @see https://www.maileva.com/catalogue-api/envoi-et-suivi-de-maileva-lrel/
    private function getProof($sending, $recipient)
    {
        $response = Http::withToken($this->token)->get($this->baseUrl . "/global_deposit_proofs");
        return $response->body();
    }
}
