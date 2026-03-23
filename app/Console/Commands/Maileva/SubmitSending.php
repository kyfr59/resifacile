<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;
use Illuminate\Support\Facades\Storage;
use App\Models\Sending;

class SubmitSending extends Command
{
    protected $signature = 'maileva:submit-sending {sending_id}';
    protected $description = 'Submit a sending {$sending_id}';
    private $baseUrl = 'https://api.sandbox.maileva.net/registered_mail/v4';
    private $sendingId = null;

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
            $this->info('Connexion Maileva OK');

        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->sendingId = $this->argument('sending_id');

        $this->token = $token;
        $this->submitSending();
    }

    private function submitSending()
    {
        $url = $this->baseUrl."/sendings/{$this->sendingId}/submit";
        $response = Http::withToken($this->token)
            ->post($url);

        if ($response->successful()) {
            $this->info("Demande d'envoi correctement créée");
        } else {
            $this->error("Echec de la création de la demande d'envoi");
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
