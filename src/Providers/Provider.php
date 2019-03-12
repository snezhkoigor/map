<?php

declare(strict_types=1);

namespace Map\Laravel\Providers;

use Illuminate\Support\Collection;
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
     * @return Collection
     *
     * @throws \Exception
     */
    public function route(RouteQuery $query): Collection;

    /**
     * Returns the provider's name.
     *
     * @return string
     */
    public function getName(): string;
}
