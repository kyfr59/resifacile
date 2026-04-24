<?php

return [
    'base_url' => env('MAILEVA_BASE_URL'),
    'client_id' => env('MAILEVA_CLIENT_ID'),
    'client_secret' => env('MAILEVA_CLIENT_SECRET'),
    'username' => env('MAILEVA_USERNAME'),
    'password' => env('MAILEVA_PASSWORD'),
    // Destinataires des alertes e-mail des exceptions Maileva
    // Laisser vide pour ne rien envoyer
    'exception_recipients' => array_filter(
        explode(',', env('MAILEVA_EXCEPTION_RECIPIENTS', ''))
    ),
];
