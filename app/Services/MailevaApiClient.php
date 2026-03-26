<?php

namespace App\Services;

use App\Actions\GetDocumentFromSending;
use App\Actions\GetSenderFromSending;
use App\Actions\GetRecipientFromSending;
use App\Models\Sending;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MailevaApiClient
{
    private string $baseUrl;
    private string $token;

    public function __construct(
        private MailevaAuthService $auth
    ) {
        $this->baseUrl = config('maileva.base_url').'/registered_mail/v4';
        $this->token = $this->auth->getAccessToken();
    }

    public function createSending(Sending $sending): string
    {
        $sender = (new GetSenderFromSending())->handle($sending->data);
        $address = $sender->paper_address->address_lines;

        $data = [
            'name' => "Commande ".$sending->order->number,
            'custom_id' => 'sending_'.$sending->id,
            'custom_data' => time(),
            'color_printing' => true,
            'duplex_printing' => true,
            'optional_address_sheet' => false,
            'notification_email' => 'do_not_reply@maileva.com',
            'print_sender_address' => false,
            'sender_address_line_1' => $address->address_line_1,
            'sender_address_line_2' => $address->address_line_2,
            'sender_address_line_3' => $address->address_line_3,
            'sender_address_line_4' => $address->address_line_4,
            'sender_address_line_5' => $address->address_line_5,
            'sender_address_line_6' => $address->address_line_6,
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
            ->acceptJson()
            ->post($this->baseUrl.'/sendings', $data);

        if (!$response->successful()) {
            Log::channel('maileva')->error("Erreur createSending", [
                'sending_id' => $sending->id,
                'status'     => $response->status(),
                'body'       => $response->body(),
            ]);
            throw new \RuntimeException("Erreur API Maileva : ({$response->status()}): ".$response->body());
        }

        return $response['id'];
    }

    public function addDocument(string $mailevaSendingId, Sending $sending): string
    {
        $document = (new GetDocumentFromSending())->handle($sending->data);
        $uri = $document->content->uri;
        $fullPath = Storage::path($uri);

        if (!Storage::disk('public')->exists($uri)) {
            Log::channel('maileva')->error("    Erreur de création de l'envoi : le document à envoyer n'existe pas sur le serveur");

            throw new \RuntimeException(
                "Erreur lors de la création de l'envoi : le document à envoyer n'existe pas sur le serveur"
            );
        }

        $size = Storage::size($uri);
        $filename = basename($uri);

        // Preparing payload
        $documentPayload = [
            'id' => $id = (string) \Illuminate\Support\Str::uuid(),
            'type' => 'application/pdf',
            'pages_count' => 1,
            'sheets_count' => 1,
            'size' => $size,
            'converted_size' => $size,
        ];

        $response = Http::withToken($this->token)
            ->acceptJson()
            ->attach(
                'document',
                fopen($fullPath, 'r'),
                $filename
            )
            ->attach(
                'metadata',
                json_encode([
                    'priority' => 1,
                    'name' => $filename,
                    'shrink' => true,
                ]),
                'metadata.json'
            )
            ->post($this->baseUrl."/sendings/{$mailevaSendingId}/documents", $documentPayload);

        if (!$response->successful()) {

            Log::channel('maileva')->error("    Erreur de création de l'envoi", [
                'sending_id' => $sending->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException(
                "   Erreur avec l'API Maileva : ({$response->status()}): ".$response->body()
            );
        }

        $mailevaDocumentId = $response['id'];

        Log::channel('maileva')->info("    Ajout du document réussi", [
            'sending_id' => $sending->id,
            'maileva_id' => $mailevaSendingId,
            'document_id' => $mailevaDocumentId,
        ]);

        return $mailevaDocumentId;
    }

    public function addRecipient(string $mailevaSendingId, Sending $sending): string
    {
        $recipient = (new GetRecipientFromSending())->handle($sending->data);
        $address = $recipient->paper_address->address_lines;

        // Preparing payload
        $data = [
            'custom_id' => 'sending_'.$sending->id,
            'custom_data' => time(),
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'address_line_3' => $address->address_line_3,
            'address_line_4' => $address->address_line_4,
            'address_line_5' => $address->address_line_5,
            'address_line_6' => $address->address_line_6,
            'country_code' => 'FR',
        ];

        $url = $this->baseUrl."/sendings/{$mailevaSendingId}/recipients";
        $response = Http::withToken($this->token)->post($url, $data);

        if (!$response->successful()) {

            Log::channel('maileva')->error("    Erreur de création du destinataire", [
                'sending_id' => $sending->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException(
                "   Erreur avec l'API Maileva : ({$response->status()}): ".$response->body()
            );
        }

        $mailevaRecipientId = $response['id'];

        Log::channel('maileva')->info("    Ajout du destinataire réussi", [
            'sending_id' => $sending->id,
            'maileva_id' => $mailevaSendingId,
            'recipient_id' => $mailevaRecipientId,
        ]);

        return $mailevaRecipientId;
    }

    public function deleteSending(string $mailevaSendingId): void
    {
        $response = Http::withToken($this->token)
            ->delete($this->baseUrl . "/sendings/{$mailevaSendingId}");

        if ($response->status() !== 204) {
            Log::channel('maileva')->error("Erreur de suppression de l'envoi", [
                'maileva_sending_id' => $mailevaSendingId,
                'status'             => $response->status(),
                'body'               => $response->body(),
            ]);

            throw new \RuntimeException(
                "Erreur suppression Maileva : ({$response->status()}): ".$response->body()
            );
        }

        Log::channel('maileva')->info("    Suppression de l'envoi réussie", [
            'maileva_sending_id' => $mailevaSendingId,
        ]);
    }

    public function submitSending(string $mailevaSendingId): void
    {
        // Fake for instant
        Http::fake([
            '*/sendings/*/submit' => Http::response(
                null,
                204
            ),
        ]);

        $response = Http::withToken($this->token)
            ->post($this->baseUrl . "/sendings/{$mailevaSendingId}/submit");


        if (!$response->successful()) {
            Log::channel('maileva')->error("   Erreur de soumission de l'envoi", [
                'maileva_sending_id' => $mailevaSendingId,
                'status'             => $response->status(),
                'body'               => $response->body(),
            ]);

            throw new \RuntimeException(
                "Erreur soumission Maileva : ({$response->status()}): " . $response->body()
            );
        }

        Log::channel('maileva')->info("    Soumission de l'envoi réussie", [
            'maileva_sending_id' => $mailevaSendingId,
        ]);
    }

    /**
     *
     * @retuns Array :
     * - The registered number
     * - The proof PDF file
     */
    public function getProofOfDepositAndRegisteredNumber($sendingId): array
    {
        $recipient = $this->getRecipientFromSendingId($sendingId);
        $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sendingId}/recipients/{$recipient->id}/download_deposit_proof");
        if (!$response->successful()) {
            throw new \RuntimeException(
                "Preuve de dépôt indisponible pour {$sendingId} : ({$response->status()}) {$response->body()}"
            );
        }

        return [
            $recipient->registered_number,
            $response->body()
        ];

    }

    private function getRecipientFromSendingId($sendingId)
    {
        $response = Http::withToken($this->token)->get($this->baseUrl . "/sendings/{$sendingId}/recipients");
        $recipient = json_decode($response->body());
        return $recipient->recipients[0];
    }
}