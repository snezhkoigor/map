# Geocode for Laravel

## Requirements
- PHP >= 7.1.3
- Laravel >= 5.6

## Installation
1. Install the package via composer:
  ```sh
  composer require snezhkoigor/geocoding
  ```
2. **If you are running Laravel 5.5 (the package will be auto-discovered), skip
  this step.** Find the `providers` array key in `config/app.php` and register
  the **Geocoding Service Provider**:
  ```php
  // 'providers' => [
      Geocoding\Laravel\GeocodingServiceProvider::class,
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
use Geocoding\Laravel\Providers\DaData;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    */
    'providers' => [
        DaData::class => [
            'token' => env('DADATA_TOKEN', ''),
            'proxy' => env('DADATA_PROXY_IP', null)
        ]
    ]
];
```

### Supported Providers
1. DaData

### Customization
If you would like to make changes to the default configuration, publish and
 edit the configuration file:
```sh
php artisan vendor:publish --provider="Geocoding\Laravel\GeocodingServiceProvider" --tag="config"
```

## Usage
The service provider initializes the `geocoding` service, accessible via the
 facade `Geocoding::...` or the application helper `app('geocoding')->...`.

#### Geocoding of Address
```php
app('geocoding')->geocode((\Geocoding\Laravel\Models\Query\GeocodeQuery::create('Санкт-Петербург')));
```

#### Suggest and get Collection of Addresses
```php
app('geocoding')->suggest((\Geocoding\Laravel\Models\Query\SuggestQuery::create('перво')));
```