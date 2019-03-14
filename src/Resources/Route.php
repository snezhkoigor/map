<?php

declare(strict_types=1);

namespace Map\Laravel\Resources;

use Illuminate\Support\Collection;
use Map\Laravel\Models\Coordinate;

/**
 * Class Route
 * @package Map\Laravel\Resources
 */
final class Route
{
    /**
     * Collection of Coordinate
     *
     * @var Collection
     */
    private $way_points;

    /**
     * @var
     */
    private $provided_by;

    /**
     * @param $provided_by
     * @param array $way_points
     */
    public function __construct($provided_by, $way_points = [])
    {
        $this->way_points = collect($way_points);
        $this->provided_by = $provided_by;
    }

    /**
     * @param string $text
     */
    public function setProvidedBy(string $text)
    {
        $this->provided_by = $text;
    }

    /**
     * @param Coordinate $coordinate
     */
    public function addWayPoint(Coordinate $coordinate)
    {
        $this->way_points->push($coordinate);
    }

    /**
     * @retrun Collection
     */
    public function getWayPoints(): Collection
    {
        return $this->way_points;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'provided_by' => $this->provided_by,
            'way_points' => $this->way_points->map(function (Coordinate $coordinate) { return $coordinate->toArray(); }),
        ];
    }
}
