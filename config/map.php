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
            'route_key' => env('YANDEX_ROUTING_KEY', ''),
            'routing_api_version' => env('YANDEX_ROUTING_API_VERSION', null),
            'proxy' => env('YANDEX_PROXY_IP', null),
            'proxy_port' => env('YANDEX_PROXY_PORT', 80)
        ],
        Tomtom::class => [
            'route_key' => env('TOMTOM_ROUTING_KEY', ''),
            'routing_api_version' => env('TOMTOM_ROUTING_API_VERSION', null),
            'proxy' => env('TOMTOM_PROXY_IP', null),
            'proxy_port' => env('TOMTOM_PROXY_PORT', 80)
        ]
    ]
];
