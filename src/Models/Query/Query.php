<?php

declare(strict_types=1);

namespace Map\Laravel\Models\Query;

use Map\Laravel\Models\Coordinate;
use Illuminate\Support\Collection;

/**
 * Interface Query
 * @package Map\Laravel\Models\Query
 */
interface Query
{
    /**
     * @param Coordinate $point
     *
     * @return Query
     */
    public function withThroughPoint(Coordinate $point);

    /**
     * @return Collection
     */
    public function getThroughPoints(): Collection;

    /**
     * @return string
     */
    public function __toString();
}