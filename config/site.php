<?php

return [
    'mail_reponse_maileva' => env('MAIL_REPONSE_MAILEVA'),
    'type' => env('TYPE_APP', App\Enums\AppType::TERMINATION_LETTER->value),
];
