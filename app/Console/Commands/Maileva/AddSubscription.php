<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class AddSubscription extends Command
{
    protected $signature = 'maileva:add-subscription';
    protected $description = 'Create notification';

    public function handle(MailevaAuthService $auth)
    {
        $token = $auth->getAccessToken();

        // Inscription à un abonnement
        $response = Http::withToken($token)
        ->acceptJson()
        ->post('https://api.sandbox.maileva.net/notification_center/v2/subscriptions', [
            "event_type"     => "ON_MAIN_DELIVERY_STATUS_UNDELIVERED",
            "resource_type"  => "registered_mail/v4/sendings",
            "callback_url"   => "https://webhook.site/76d13e1f-ad81-4578-ab88-1d7a89a17c9e",
            "authentication" => [
                "basic" => [
                    "login"    => config('maileva.username'),
                    "password" => config('maileva.password'),
                ]
            ]
        ]);

        // Liste des abonnements
        /*
        $response = Http::withToken($token)
        ->get('https://api.sandbox.maileva.net/notification_center/v2/subscriptions');
        */

        // Détail d'un abonnement
        /*
        $subscriptionId = '2305bf46-eab6-4505-a566-304fb4e48e22';
        $response = Http::withToken($token)
        ->get('https://api.sandbox.maileva.net/notification_center/v2/subscriptions/'.$subscriptionId);
        */

        dd(json_decode($response->body()));
    }
}

