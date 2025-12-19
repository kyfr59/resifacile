<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCampaign;
use App\Models\Campaign;
use Illuminate\Console\Command;

class ExecuteCampaignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:single {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Campagne spécifigique envoyée à Maileva';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaign = Campaign::find($this->argument('id'));
        ProcessCampaign::dispatch($campaign->data);

        return Command::SUCCESS;
    }
}
