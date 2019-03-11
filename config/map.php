<?php

use Map\Laravel\Providers\Yandex;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    */
    'providers' => [
        Yandex::class => [
            'key' => env('YANDEX_ROUTE_KEY', ''),
            'proxy' => env('YANDEX_PROXY_IP', null)
        ]
    ]
];
