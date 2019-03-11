<?php

declare(strict_types=1);

namespace Map\Laravel\Resources;

use Map\Models\Coordinate;
use Illuminate\Support\Collection;

/**
 * Class Route
 * @package Map\Laravel\Resources
 */
final class Route
{
    /**
     * @var Collection
     */
    private $points;

    /**
     * @var
     */
    private $provided_by;

    /**
     * @param array $points
     * @param $provided_by
     */
    public function __construct($provided_by, array $points = [])
    {
        $this->points = coolect($points);
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
    public function setPoint(Coordinate $coordinate)
    {
        $this->points->push($coordinate);
    }

    /**
     * @retrun Collection
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'provided_by' => $this->provided_by,
            'points' => $this->points
        ];
    }
}
