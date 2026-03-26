<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sending;

class SimulateWebhook extends Command
{
    protected $signature = 'maileva:simulate-webhook
                            {resource_id : ID du sending Maileva}
                            {--event=ON_STATUS_PROCESSED : Type d\'événement}
                            {--custom_id= : resource_custom_id (ex: sending_42)}
                            {--custom_data= : resource_custom_data (ex: 1774338919)}';

    protected $description = 'Simule un webhook Maileva en local';

    public function handle(): int
    {
        $sending = Sending::fromMailevaId($this->argument('resource_id'));

        $payload = [
            'source'              => 'api.maileva.com',
            'user_id'             => '2335',
            'client_id'           => 'sandbox-KOLIBRI',
            'event_type'          => $this->option('event'),
            'event_date'          => now()->toIso8601String(),
            'resource_type'       => 'registered_mail/v4/sendings',
            'resource_id'         => $this->argument('resource_id'),
            'resource_location'   => 'https://api.sandbox.maileva.net/registered_mail/v4/sendings/' . $this->argument('resource_id'),
            'resource_name'       => 'sendings',
            'resource_custom_id'  => 'sending_'.$sending->id,
            'resource_custom_data'=> $this->option('custom_data'),
            'event_detail'        => null,
        ];

        $this->info('Envoi du webhook simulé...');
        $this->line(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::
            withBasicAuth('admin', 'Tinyfox')
            ->post(config('app.url') . '/webhook-maileva', $payload);

        if ($response->successful()) {
            $this->info("✅ Réponse {$response->status()}");
        } else {
            $this->error("❌ Erreur {$response->status()} : {$response->body()}");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}