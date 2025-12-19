<?php

namespace App\Jobs;

use App\Mail\ProofOfDepositRecieved;
use App\Mail\UnsubscribeDemande;
use App\Models\Customer;
use App\Models\MailReceive;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\WebhookClient\Models\WebhookCall;

class MailRecieved implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var WebhookCall
     */
    public WebhookCall $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $mailRecieved = MailReceive::create([
            'message_id' => $this->webhookCall->payload['MessageID'],
            'from' => $this->webhookCall->payload['OriginalRecipient'],
            'subject' => $this->webhookCall->payload['Subject'],
            'body' => $this->webhookCall->payload['TextBody'],
            'type' => $this->webhookCall->payload['Tag'],
            'payload' => $this->webhookCall,
        ]);

        $user = Customer::where('email', $this->webhookCall->payload['OriginalRecipient'])->first();
        $user?->mailRecieves->save($mailRecieved);

        if($mailRecieved->from === config('site.mail_reponse_maileva')) {
            foreach ($this->webhookCall->payload['Attachments'] as $attachment) {
                Storage::put('proof_of_deposit/' . $attachment['Name'], base64_decode($attachment['Content']));
            }
        }
    }
}
