<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class CreateSending extends Command
{
    protected $signature = 'maileva:create-sending';
    protected $description = 'Create sending';
    private $baseUrl = 'https://api.sandbox.maileva.net/mail/v2';

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->token = $token;
        $this->createNewSending();
    }

    private function createNewSending()
    {
        $data = [
            'name' => "Résiiation de Franck",
            'custom_id' => time(),
            'custom_data' => time(),
            'color_printing' => true,
            'duplex_printing' => true,
            'optional_address_sheet' => false,
            'notification_email' => 'do_not_reply@maileva.com',
            'print_sender_address' => false,
            'sender_address_line_1' => 'Société Durand',
            'sender_address_line_2' => 'M. Franck DUPONT',
            'sender_address_line_3' => 'Batiment B',
            'sender_address_line_4' => '10 avenue Charles de Gaulle',
            'sender_address_line_5' => '',
            'sender_address_line_6' => '94673 Charenton-Le-Pont',
            'sender_country_code' => 'FR',
            'archiving_duration' => 0,
            'envelope_windows_type' => 'SIMPLE',
            'postage_type' => 'FAST',
            'treat_undelivered_mail' => false,
            'notification_treat_undelivered_mail' => [
                'email@domain.com',
                'email_bis@domain.com',
            ],
        ];

        $response = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($this->baseUrl . '/sendings', $data);


        if ($response->successful()) {
            $this->info("Identifiant de l'envoi créé : ".$response['id']);
        } else {
            $this->error("Echec de la création de l'envoi");
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
