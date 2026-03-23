<?php

namespace App\Jobs;

//use App\Actions\GetAllDocumentsFromCampaign;
//use App\Actions\MakeCouFromCampaign;
//use App\Actions\MakeXMLFromCampaign;
use App\Contracts\PostLetter;
use App\Models\Sending;
//use App\DataTransferObjects\PostLetter\SendingData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

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
    public function handle(): void
    {
        App::make(PostLetter::class)
            ->pushSending(
                sending: $this->sending,
            );
    }
}
