<?php

declare(strict_types=1);

namespace Map\Laravel;

use Map\Laravel\Facades\Map;
use Map\Laravel\Providers\Aggregator;
use Illuminate\Support\ServiceProvider;

/**
 * Class MapServiceProvider
 * @package Map\Laravel
 */
class MapServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/map.php';

        $this->publishes(
            [ $configPath => $this->configPath('map.php') ],
            'config'
        );

        $this->mergeConfigFrom($configPath, 'map');
    }

    /**
     *
     */
    public function register()
    {
        $this->app->alias('map', Map::class);

        $this->app->singleton(Aggregator::class, function () {
            return (new Aggregator())
                ->registerProvidersFromConfig(collect(config('map.providers')));
        });

        $this->app->bind('map', Aggregator::class);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function configPath(string $path = '') : string
    {
        if (function_exists('config_path')) {
            return config_path($path);
        }

        $pathParts = [
            app()->basePath(),
            'config',
            trim($path, '/'),
        ];

        return implode('/', $pathParts);
    }
}