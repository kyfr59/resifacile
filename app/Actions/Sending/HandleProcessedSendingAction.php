<?php

namespace App\Actions\Sending;

use App\Enums\SendingStatus;
use App\Models\Sending;
use App\Mail\Admin\MailevaWebhookError;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailevaService;

class HandleProcessedSendingAction
{
    public function __construct(
        private readonly MailevaService $mailevaService
    ) {}


    private function notifyAdmin(string $message, array $context = []): void
    {
        Log::error($message, $context);
        Mail::to(config('mail.admin_email'))->send(new MailevaWebhookError($message, $context));
    }

    public function execute(array $payload): void
    {
        // Payload validity checkings

        if (empty($payload['resource_custom_id'])) {
            $this->notifyAdmin('HandleProcessedSendingAction: resource_custom_id manquant', ['payload' => $payload]);
            throw new \RuntimeException('HandleProcessedSendingAction: resource_custom_id manquant');
        }

        $parts = explode('_', $payload['resource_custom_id']);
        if (count($parts) < 2 || !is_numeric($parts[1])) {
            $this->notifyAdmin('HandleProcessedSendingAction: Format de resource_custom_id invalide', ['payload' => $payload]);
            throw new \RuntimeException('HandleProcessedSendingAction: Format de resource_custom_id invalide');
        }

        $sending = Sending::find($parts[1]);
        if (!$sending) {
            $this->notifyAdmin('HandleProcessedSendingAction: Envoi introuvable', ['payload' => $payload]);
            throw new \RuntimeException('HandleProcessedSendingAction: Envoi introuvable');
        }

        if ($sending->status === SendingStatus::PROCESSED) {
            $this->notifyAdmin('HandleProcessedSendingAction: Envoi déjà traité', ['payload' => $payload]);
            throw new \RuntimeException('HandleProcessedSendingAction: Envoi déjà traité');
        }

        // Get and store proof of déposit

        $pdf = $this->mailevaService->storeProofOfDeposit($sending);

        //Mail::to($sending->user->email)->send(new SendingConfirmation($sending, $pdf));

        $sending->status = SendingStatus::PROCESSED;
        $sending->executed_at = now();
        $sending->save();

        Log::info('HandleProcessedSendingAction: Sending mis à jour', ['sending_id' => $sending->id]);
    }
}