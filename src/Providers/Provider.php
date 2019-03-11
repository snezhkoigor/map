<?php

declare(strict_types=1);

namespace Map\Laravel\Providers;

use Map\Laravel\Resources\Route;
use Map\Laravel\Models\Query\RouteQuery;

/**
 * Interface Provider
 * @package Map\Laravel\Providers
 */
interface Provider
{
    /**
     * @param RouteQuery $query
     *
     * @return Route|null
     *
     * @throws \Exception
     */
    public function route(RouteQuery $query): ?Route;

    /**
     * Returns the provider's name.
     *
     * @return string
     */
    public function getName(): string;
}
