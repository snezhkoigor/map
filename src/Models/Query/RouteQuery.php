<?php

namespace Map\Laravel\Models\Query;

use Map\Laravel\Models\Coordinate;
use Illuminate\Support\Collection;

/**
 * Class RouteQuery
 * @package Map\Laravel\Models\Query
 */
class RouteQuery implements Query
{
    /**
     * @var Collection
     */
    private $through_points;

    /**
     * RouteQuery constructor.
     */
    public function __construct()
    {
        $this->through_points = collect([]);
    }

    /**
     * @param Coordinate $point
     * @return RouteQuery
     */
    public function withThroughPoint(Coordinate $point): self
    {
        $new = clone $this;
        $new->through_points->push($point);

        return $new;
    }

    /**
     * @return Coordinate[]
     */
    public function getThroughPoints(): array
    {
        return $this->through_points;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('RouteQuery: %s', json_encode([
            'through_points' => $this->getThroughPoints()->toArray()
        ]));
    }
}
