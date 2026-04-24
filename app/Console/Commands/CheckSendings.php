<?php

namespace App\Console\Commands;

use App\Models\Sending;
use App\Enums\SendingsStatus;
use App\Enums\SendingStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSendings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-sendings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie que les envois ne soient pas bloqués sur un statut depuis plus de 2 jours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendings = Sending::all();
        $ids = $sendings->pluck('id')->implode(',');
        $errors = [];

        foreach ($sendings as $sending) {
            switch ($sending->status) {
                case SendingStatus::WAITING:
                    if (empty($sending->sended_at)) {
                        if ($sending->waiting_at->diffInDays(now()) >= 2) {
                            $errors[] = "\r\nL'envoi {$sending->id} est en attente d'envoi (passage à SENDED) depuis plus de 2 jours";
                            $errors[] = "https://preprod.resifacile.fr/admin/sendings/{$sending->id}";
                        }
                    }
                    break;
                case SendingStatus::SENDED:
                    if (empty($sending->accepted_at)) {
                        if ($sending->sended_at->diffInDays(now()) >= 2) {
                            $errors[] = "\r\nL'envoi {$sending->id} est en attente d'acceptation (passage à ACCEPTED) depuis plus de 2 jours";
                            $errors[] = "https://preprod.resifacile.fr/admin/sendings/{$sending->id}";
                        }
                    }
                    break;
                case SendingStatus::ACCEPTED:
                    if (empty($sending->processed_at)) {
                        if ($sending->accepted_at->diffInDays(now()) >= 2) {
                            $errors[] = "\r\nL'envoi {$sending->id} est en attente de traitement (passage à PROCESSED) depuis plus de 2 jours";
                            $errors[] = "https://preprod.resifacile.fr/admin/sendings/{$sending->id}";
                        }
                    }
                    break;
                case SendingStatus::PROCESSED:
                    // Rien pour l'instant
                    break;
            }
        }

        if (count($errors)) {

            Mail::raw(implode("\r\n", $errors)
                , function ($message) {
                $message
                    ->to(config('maileva.exception_recipients'))
                    ->subject("[" . config('app.name') . "] Envois Maileva en retard");
            }
            );
        }
    }
}
