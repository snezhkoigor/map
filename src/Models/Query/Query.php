<?php

declare(strict_types=1);

namespace Map\Laravel\Models\Query;

use Map\Models\Coordinate;

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
     * @return Coordinate[]
     */
    public function getThroughPoints(): array;

    /**
     * @return string
     */
    public function __toString();
}