<?php

namespace Map\Laravel\Providers;

use Map\Laravel\Models\Query\RouteQuery;
use Map\Laravel\Resources\Route;
use Illuminate\Support\Collection;
use Map\Laravel\Exceptions\InvalidServerResponse;

class Aggregator implements Provider
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @param RouteQuery $query
     * @return Route|null
     */
    public function route(RouteQuery $query): ?Route
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->route($query);

                if ($result instanceof Route) {
                    return $result;
                }
            } catch (\Throwable $e) {
                throw InvalidServerResponse::create('Provider "' . $provider->getName() . '" could not geocode address: "' . $query->getText() . '".');
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'aggregator';
    }

    /**
     * @param Collection $providers
     * @return Aggregator
     */
    public function registerProvidersFromConfig(Collection $providers): self
    {
        $this->providers = $this->getProvidersFromConfiguration($providers);

        return $this;
    }

    /**
     * @param Collection $providers
     * @return array
     */
    protected function getProvidersFromConfiguration(Collection $providers) : array
    {
        $providers = $providers->map(function ($arguments, $provider) {
            $reflection = new \ReflectionClass($provider);

            return $reflection->newInstanceArgs($arguments);
        });

        return $providers->toArray();
    }
}
