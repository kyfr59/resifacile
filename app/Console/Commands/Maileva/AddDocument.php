<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;
use Illuminate\Support\Facades\Storage;
use App\Models\Sending;

class AddDocument extends Command
{
    protected $signature = 'maileva:add-document {sending_id}';
    protected $description = 'Add document existing sending {$sending_id}';
    private $baseUrl = 'https://api.sandbox.maileva.net/mail/v2';
    private $sendingId = null;

    public function handle(MailevaAuthService $auth)
    {
        try {
            $token = $auth->getAccessToken();
        } catch (\Throwable $e) {
            $this->error('Erreur connexion Maileva');
            $this->error($e->getMessage());
        }

        $this->sendingId = $this->argument('sending_id');

        $this->token = $token;
        $this->createNewSending();
    }

    private function createNewSending()
    {
        // Retrieve the document of a sending
        $sending = Sending::latest()->first();
        $request = $sending->data->requests->first();
        $document = $request->documentData->first();
        $documentPath = $document->content->uri;
        $fullPath = storage_path('app/' . $documentPath);
        $size = Storage::size($documentPath);

        // Preparing payload
        $documentPayload = [
            'id' => $id = (string) \Illuminate\Support\Str::uuid(),
            'type' => 'application/pdf',
            'pages_count' => 1,
            'sheets_count' => 1,
            'size' => $size,
            'converted_size' => $size,
        ];

        // Document sending
        $url = $this->baseUrl."/sendings/{$this->sendingId}/documents";
        $response = Http::withToken($this->token)
            ->acceptJson()
            ->attach(
                'document',
                fopen($fullPath, 'r'),
                basename($fullPath)
            )
            ->attach(
                'metadata',
                json_encode([
                    'priority' => random_int(1, 1000),
                    'name' => basename($fullPath),
                    'shrink' => true,
                ]),
                'metadata.json'
            )
            ->post($url, $documentPayload);

        if ($response->successful()) {
            $this->info("Identifiant du document créé : ".$response['id']);
        } else {
            $this->error("Echec de la création de l'envoi");
            $this->error('Status: ' . $response->status());
            $this->error('Body: ' . $response->body());
        }
    }
}
