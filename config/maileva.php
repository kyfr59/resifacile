<?php

return [
    'mode' => env('MAILEVA_MODE'),
    'base_url' => env('MAILEVA_BASE_URL'),
    'connection_url' => env('MAILEVA_CONNECTION_URL'),
    'client_id' => env('MAILEVA_CLIENT_ID'),
    'client_secret' => env('MAILEVA_CLIENT_SECRET'),
    'username' => env('MAILEVA_USERNAME'),
    'password' => env('MAILEVA_PASSWORD'),
    'exception_recipients' => array_filter(
        explode(',', env('MAILEVA_EXCEPTION_RECIPIENTS', ''))
    ),
];
