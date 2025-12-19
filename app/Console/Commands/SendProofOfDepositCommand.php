<?php

namespace App\Console\Commands;

use App\Jobs\MailRecieved;
use App\Mail\ProofOfDepositRecieved;
use App\Models\Customer;
use App\Models\MailReceive;
use App\Models\Tracking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendProofOfDepositCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-proof';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoi des preuves légales de dépôt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mailRecieves = MailReceive::where('from', config('site.mail_reponse_maileva'))->whereNull('customer_id')->get();
        $mailRecieves->each(function($mailRevieve) {
            $numberTracking = last(explode(' ', $mailRevieve->subject));
            $tracking = Tracking::where('number', $numberTracking)->first();

            if($tracking) {
                $mailRevieve->customer()->associate($tracking->customer);
                $mailRevieve->save();

                try {
                    Mail::to($tracking->customer->email)->send(new ProofOfDepositRecieved($mailRevieve->payload));
                } catch (\Exception $e) {
                    Log::error($e);
                }
            }
        });
    }
}
