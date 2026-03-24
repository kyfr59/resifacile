<?php

namespace App\Mail\Admin;

use Illuminate\Mail\Mailable;

class MailevaWebhookError extends Mailable
{
    public function __construct(
        public string $errorMessage,
        public array $context = []
    ) {}

    public function build()
    {
        return $this->subject('[Erreur Webhook Maileva] ' . $this->errorMessage)
                    ->html($this->buildHtml());
    }

    private function buildHtml(): string
    {
        $context = json_encode($this->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<HTML
            <h2>Erreur dans HandleProcessedSendingAction</h2>
            <p><strong>Message :</strong> {$this->errorMessage}</p>
            <h3>Contexte :</h3>
            <pre style="background:#f3f3f3;padding:12px;border-radius:4px">{$context}</pre>
            <hr>
            <small>Environnement : {$_ENV['APP_ENV']}</small>
        HTML;
    }
}