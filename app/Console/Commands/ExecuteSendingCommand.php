<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSending;
use App\Models\Sending;
use Illuminate\Console\Command;

class ExecuteSendingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sending:single {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoi spécifique envoyé à Maileva';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sending = Sending::find($this->argument('id'));
        ProcessSending::dispatch($sending);

        return Command::SUCCESS;
    }
}
