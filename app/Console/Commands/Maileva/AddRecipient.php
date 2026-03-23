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
    private $baseUrl = 'https://api.sandbox.maileva.net/registered_mail/v4';
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
            'address_line_1' => 'La Poste',
            'address_line_2' => 'Me Eva DUPONT',
            'address_line_3' => 'Résidence des Peupliers',
            'address_line_4' => '33 avenue de Paris',
            'address_line_5' => 'BP 356',
            'address_line_6' => '75000 Paris',
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
