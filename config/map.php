<?php

use Map\Laravel\Providers\Yandex;
use Map\Laravel\Providers\Tomtom;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    */
    'providers' => [
        Yandex::class => [
            'route_key' => env('YANDEX_ROUTE_KEY', ''),
            'routing_api_version' => env('YANDEX_ROUTING_API_VERSION', null),
            'proxy' => env('YANDEX_PROXY_IP', null)
        ],
        Tomtom::class => [
            'route_key' => env('TOMTOM_ROUTE_KEY', ''),
            'routing_api_version' => env('TOMTOM_ROUTING_API_VERSION', null),
            'proxy' => env('TOMTOM_PROXY_IP', null)
        ]
    ]
];
