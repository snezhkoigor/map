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
     * @param string $type
     * @return Query
     */
    public function withRouteType(string $type);

    /**
     * @return string
     */
    public function getRouteType(): string;

    /**
     * @return Query
     */
    public function withTraffic();

    /**
     * @return Query
     */
    public function withoutTraffic();

    /**
     * @return bool
     */
    public function getTraffic(): bool;

    /**
     * @return Query
     */
    public function withAvoidTollsRoads();

    /**
     * @return Query
     */
    public function withoutAvoidTollsRoads();

    /**
     * @return bool
     */
    public function getAvoidTollsRoads(): bool;

    /**
     * @param string $travel_mode
     *
     * @return Query
     */
    public function withTravelMode(string $travel_mode);

    /**
     * @return string|null
     */
    public function getTravelMode(): ?string;

    /**
     * @param string $locale
     *
     * @return Query
     */
    public function withLocale(string $locale);

    /**
     * @return string|null
     */
    public function getLocale(): ?string;

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