<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailevaExceptionMail;

/*
Exemple d'exception levée manuellement :
throw new MailevaException(
    message    : 'Dépôt du courrier échoué.',
    context    : 'MailevaService::deposit',
    extraData  : ['courrier_id' => $id],
    description: "Le dépôt a été déclenché automatiquement lors de la clôture du dossier.\n"
            . "Vérifier que le token API n'est pas expiré et que le PDF joint fait moins de 10 Mo.",
);
*/

class MailevaException extends Exception
{
    protected string $context;
    protected string $description;
    protected array $extraData;

    public function __construct(
        string $message = '',
        string $context = '',
        array $extraData = [],
        int $code = 0,
        ?\Throwable $previous = null,
        string $description = '',
    ) {
        $this->context     = $context;
        $this->description = $description;
        $this->extraData   = $extraData;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Appelé automatiquement par Laravel lors du report de l'exception.
     */
    public function report(): void
    {
        $this->writeLog();
        $this->sendMail();
    }

    // -------------------------------------------------------------------------
    // Logging
    // -------------------------------------------------------------------------

    protected function writeLog(): void
    {
        Log::channel('maileva')->error($this->buildLogMessage(), $this->buildLogContext());
    }

    protected function buildLogMessage(): string
    {
        return sprintf(
            '[MailevaException]%s %s',
            $this->context ? " [{$this->context}]" : '',
            $this->getMessage()
        );
    }

    protected function buildLogContext(): array
    {
        $ctx = [
            'code'     => $this->getCode(),
            'file'     => $this->getFile(),
            'line'     => $this->getLine(),
            'trace'    => $this->getTraceAsString(),
            'previous' => $this->getPrevious()?->getMessage(),
        ];

        if ($this->description) {
            $ctx['description'] = $this->description;
        }

        return array_merge($ctx, $this->extraData);
    }

    // -------------------------------------------------------------------------
    // Mail
    // -------------------------------------------------------------------------

    protected function sendMail(): void
    {
        $recipients = config('maileva.exception_recipients', []);

        if (empty($recipients)) {
            return;
        }

        try {
            Mail::to($recipients)->send(new MailevaExceptionMail($this));
        } catch (\Throwable $e) {
            Log::channel('maileva')->error(
                '[MailevaException] Impossible d\'envoyer le mail d\'alerte : ' . $e->getMessage()
            );
        }
    }

    // -------------------------------------------------------------------------
    // Accesseurs
    // -------------------------------------------------------------------------

    public function getContext(): string     { return $this->context; }
    public function getDescription(): string { return $this->description; }
    public function getExtraData(): array    { return $this->extraData; }
}