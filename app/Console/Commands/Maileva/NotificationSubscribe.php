<?php

namespace App\Console\Commands\Maileva;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\MailevaAuthService;

class NotificationSubscribe extends Command
{
    protected $signature = 'maileva:notification-subscribe';
    protected $description = 'Create notification';

    public function handle(MailevaAuthService $auth)
    {
        $token = $auth->getAccessToken();

        // Inscription à un abonnement
        /*
        $response = Http::withToken($token)
        ->acceptJson()
        ->post('https://api.sandbox.maileva.net/notification_center/v2/subscriptions', [
            "event_type"     => "ON_STATUS_ACCEPTED",
            "resource_type"  => "mail/v2/sendings",
            "callback_url"   => "https://webhook.site/f44a30c7-7c32-44da-bc32-822bff9a77a6",
            "authentication" => [
                "basic" => [
                    "login"    => config('maileva.username'),
                    "password" => config('maileva.password'),
                ]
            ]
        ]);
        */

        // Liste des abonnements
        /*
        $response = Http::withToken($token)
        ->get('https://api.sandbox.maileva.net/notification_center/v2/subscriptions');
        */

        // Détail d'un abonnement
        $subscriptionId = '2305bf46-eab6-4505-a566-304fb4e48e22';
        $response = Http::withToken($token)
        ->get('https://api.sandbox.maileva.net/notification_center/v2/subscriptions/'.$subscriptionId);

        dd(json_decode($response->body()));
    }
}

