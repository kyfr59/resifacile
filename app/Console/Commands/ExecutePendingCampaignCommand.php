<?php

namespace App\Console\Commands;

use App\Enums\CampaignStatus;
use App\Jobs\ProcessCampaign;
use App\Mail\CampaignExecuted;
use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExecutePendingCampaignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Campagne envoyée à Maileva';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $campaigns = Campaign::where('status', CampaignStatus::PENDING_EXECUTION->name)
            ->limit(1)
            ->get();

        $campaigns->each(static function($campaign) {
            ProcessCampaign::dispatch($campaign->data);
            $campaign->status = CampaignStatus::EXECUTED;
            $campaign->executed_at = now();
            $campaign->save();

            Mail::to($campaign->customer)->send(new CampaignExecuted($campaign));
        });

        return Command::SUCCESS;
    }
}
