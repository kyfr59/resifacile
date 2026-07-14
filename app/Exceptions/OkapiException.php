<?php

namespace App\Exceptions;

use Exception;

class OkapiException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $httpStatus = null,
        public readonly ?array $payload = null,
    ) {
        parent::__construct($message);
    }
}