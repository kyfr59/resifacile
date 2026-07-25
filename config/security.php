<?php

return [
    'allowed_ips' => array_filter(array_map('trim', explode(',', env('ALLOWED_IPS', '')))),
];