<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;
use Illuminate\Support\Facades\Storage;
use App\Models\Sending;

class AddRecipient extends Command
{
    protected $signature = 'maileva:add-recipient {sending_id}';
    protected $description = 'Add recipient existing sending {$sending_id}';
    private $baseUrl = 'https://api.maileva.com/registered_mail/v4';
    private $sendingId = null;

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->token = $token;
        $this->sendingId = $this->argument('sending_id');

        $this->addRecipient();
    }

    private function addRecipient()
    {
        // Preparing payload
        $data = [
            'custom_id' => 'custom',
            'custom_data' => 'order_1234',
            'address_line_1' => '4AP SARL', // Société
            'address_line_2' => '', // Civilité, nom, prénom
            'address_line_3' => '', // Résidence, batiment
            'address_line_4' => '19 boulevard lelasseur', // Numéro et libellé de la voie
            'address_line_5' => '', // Lieu dit, boite postale
            'address_line_6' => '44000 Nantes', // Code postal et ville
            'country_code' => 'FR',
            /*
            'documents_override' => [
                [
                    'document_id' => '6dfe84bc-3428-43db-90b5-ff9ac3b68ac2',
                    'start_page' => '1',
                    'end_page' => '3',
                ],
            ],
            */
        ];

        $url = $this->baseUrl."/sendings/{$this->sendingId}/recipients";
        $response = Http::withToken($this->token)
            ->post($url, $data);

        if ($response->successful()) {
            $this->info("Identifiant du destinataire créé : ".$response['id']);
        } else {
            $this->error("Echec de la création de l'envoi");
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
