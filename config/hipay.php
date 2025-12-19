<?php

return [
    'default' => env('HIPAY1_MID'),
    env('HIPAY1_MID') => [
        'mid' => env('HIPAY1_MID'),
        'user' => env('HIPAY1_USER'),
        'password' => env('HIPAY1_PASSWORD'),
        'env' => env('HIPAY1_ENV', 'production'),
        'passphrase' =>  env('HIPAY1_PASSPHRASE'),
    ],
    env('HIPAY2_MID') => [
        'mid' => env('HIPAY2_MID'),
        'user' => env('HIPAY2_USER'),
        'password' => env('HIPAY2_PASSWORD'),
        'env' => env('HIPAY2_ENV', 'production'),
        'passphrase' =>  env('HIPAY2_PASSPHRASE'),
    ],
];
