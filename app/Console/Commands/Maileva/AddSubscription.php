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
        ->post('https://api.maileva.com/notification_center/v2/subscriptions', [
            "event_type"     => "ON_STATUS_PROCESSED",
            "resource_type"  => "registered_mail/v4/sendings",
            "callback_url"   => "https://resifa12:Kolibri-4478!@preprod.resifacile.fr/webhook-maileva",
            "authentication" => [
                "basic" => [
                    "login"    => config('maileva.username'),
                    "password" => config('maileva.password'),
                ]
            ]
        ]);

        dd(json_decode($response->body()));
    }
}

