<?php

namespace App\Console\Commands;

use Throwable;
use App\Models\Sending;
use App\Enums\SendingStatus;
use App\Jobs\ProcessSending;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ExecuteSendingsCommand extends Command
{
    protected $signature = 'execute-sendings';
    protected $description = 'Envoi tous les envois en attente à Maileva';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sendings = Sending::where('status', SendingStatus::DRAFT)->limit(1)->get();
        $ids = $sendings->pluck('id')->implode(',');

        Log::channel('maileva')->info("  ");
        Log::channel('maileva')->info("### Début d'une session d'envoi [$ids]");
        Log::channel('maileva')->info("  ");

        foreach ($sendings as $sending) {
            ProcessSending::dispatch($sending);
        }

        return Command::SUCCESS;
    }
}
