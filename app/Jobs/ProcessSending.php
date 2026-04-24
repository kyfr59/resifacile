<?php

namespace App\Jobs;

use App\Mail\SendingExecuted;
use App\Contracts\PostLetter;
use App\Models\Sending;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Enums\SendingStatus;
use Illuminate\Support\Facades\Log;
use App\Services\SendingOrchestrator;
use Illuminate\Support\Facades\Mail;

class ProcessSending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Sending $sending
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SendingOrchestrator $orchestrator): void
    {
        $orchestrator->process($this->sending);

        $this->sending->status = SendingStatus::SENDED;
        $this->sending->sended_at = now();
        $this->sending->save();

        Log::channel('maileva')->info("    Statut mis à jour : SENDED", [
            'sending_id' => $this->sending->id,
        ]);
        Log::channel('maileva')->info("");
    }
}
