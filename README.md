# Geocode for Laravel

## Requirements
- PHP >= 7.1.3
- Laravel >= 5.6

## Installation
1. Install the package via composer:
  ```sh
  composer require snezhkoigor/map
  ```
2. **If you are running Laravel 5.5 (the package will be auto-discovered), skip
  this step.** Find the `providers` array key in `config/app.php` and register
  the **Map Service Provider**:
  ```php
  // 'providers' => [
      Map\Laravel\MapServiceProvider::class,
  // ];
  ```
  
### Providers
By default, the configuration specifies a Chain provider, containing the
 GoogleMaps provider for addresses as well as reverse lookups with lat/long.

However, you are free to add or remove providers as needed, both inside the
 Chain provider, as well as along-side it. The following is the default
 configuration provided by the package:
 
```
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
```

### Supported Providers
1. Yandex

### Customization
If you would like to make changes to the default configuration, publish and
 edit the configuration file:
```sh
php artisan vendor:publish --provider="Map\Laravel\MapServiceProvider" --tag="config"
```

## Usage
The service provider initializes the `map` service, accessible via the
 facade `Map::...` or the application helper `app('map')->...`.
 
# Build route
```
$way = app('map')->route(
    (new RouteQuery())
        ->withThroughPoint(new Coordinate(56.991837, 60.477136))
        ->withThroughPoint(new Coordinate(56.907375, 60.780160))
        ->withThroughPoint(new Coordinate(58.201698, 68.253762))
);
```

Result would be Collection of Coordinate class:
```
[
    {
        latitude: 
        longitude:
    },
    {
        latitude: 
        longitude:
    },
    ...
    {
        latitude: 
        longitude:
    }
]
```

# Dumpers
Package provides dumpers that aim to transform a some object in standard formats.

### Supported Dumpers
1. Keyhole Markup Language (KML). [Keyhole Markup Language](http://en.wikipedia.org/wiki/Keyhole_Markup_Language) is an XML notation for expressing geographic annotation and visualization within Internet-based, two-dimensional maps and three-dimensional Earth browsers.

```
$way = app('map')->route(
    (new RouteQuery())
        ->withThroughPoint(new Coordinate(56.991837, 60.477136))
        ->withThroughPoint(new Coordinate(56.907375, 60.780160))
        ->withThroughPoint(new Coordinate(58.201698, 68.253762))
        ->withAvoidTollsRoads()
);

$kml = (new Kml())->dumpRoute('name', $way)
```