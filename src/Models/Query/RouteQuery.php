<?php

namespace Map\Laravel\Models\Query;

use Illuminate\Support\Collection;
use Map\Laravel\Models\RouteLocale;
use Map\Laravel\Models\RouteType;
use Map\Laravel\Models\TravelMode;

/**
 * Class RouteQuery
 * @package Map\Laravel\Models\Query
 */
class RouteQuery implements Query
{
    /**
     * @var string|null
     */
    private $route_type = RouteType::ROUTE_TYPE_FASTEST;

    /**
     * @var string|null
     */
    private $locale = RouteLocale::LOCALE_RU;

    /**
     * @var Collection
     */
    private $through_points;

    /**
     * @var string
     */
    private $travel_mode = TravelMode::TRAVEL_MODE_CAR;

    /**
     * @var bool
     */
    private $avoid_tolls_roads = false;

    /**
     * @var bool
     */
    private $traffic = true;

    /**
     * RouteQuery constructor.
     */
    public function __construct()
    {
        $this->through_points = collect([]);
    }

    /**
     * @param string $type
     * @return RouteQuery
     */
    public function withRouteType(string $type): self
    {
        $new = clone $this;
        $new->route_type = $type;

        return $new;
    }

    /**
     * @return string
     */
    public function getRouteType(): string
    {
        return $this->route_type;
    }

    /**
     * @return RouteQuery
     */
    public function withTraffic(): self
    {
        $new = clone $this;
        $new->traffic = true;

        return $new;
    }

    /**
     * @return RouteQuery
     */
    public function withoutTraffic(): self
    {
        $new = clone $this;
        $new->traffic = false;

        return $new;
    }

    /**
     * @return bool
     */
    public function getTraffic(): bool
    {
        return $this->traffic;
    }

    /**
     * @param bool $value
     * @return RouteQuery
     */
    public function withAvoidTollsRoads(bool $value): self
    {
        $new = clone $this;
        $new->avoid_tolls_roads = $value;

        return $new;
    }

    /**
     * @return bool
     */
    public function getAvoidTollsRoads(): bool
    {
        return $this->avoid_tolls_roads;
    }

    /**
     * @param string $travel_mode
     *
     * @return RouteQuery
     */
    public function withTravelMode(string $travel_mode): self
    {
        $new = clone $this;
        $new->travel_mode = $travel_mode;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getTravelMode(): ?string
    {
        return $this->travel_mode;
    }

    /**
     * @param string $locale
     *
     * @return RouteQuery
     */
    public function withLocale(string $locale): self
    {
        $new = clone $this;
        $new->locale = $locale;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param Collection $points
     * @return RouteQuery
     */
    public function withThroughPoints(Collection $points): self
    {
        $new = clone $this;
        $new->through_points = $points;

        return $new;
    }

    /**
     * @return Collection
     */
    public function getThroughPoints(): Collection
    {
        return $this->through_points;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('RouteQuery: %s', json_encode([
            'through_points' => $this->getThroughPoints()->toArray(),
            'traffic' => $this->getTraffic(),
            'locale' => $this->getLocale(),
            'travel_mode' => $this->getTravelMode(),
            'avoid_tolls_roads' => $this->getAvoidTollsRoads(),
            'route_type' => $this->getRouteType()
        ]));
    }
}
