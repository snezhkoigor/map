<?php

namespace Map\Laravel\Providers;

use Map\Laravel\Exceptions\InvalidServerResponse;
use Map\Laravel\Models\Query\RouteQuery;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Map\Laravel\Models\Coordinate;
use Map\Laravel\Models\TravelMode;

/**
 * Class Tomtom
 * @package Map\Laravel\Providers
 */
class Tomtom implements Provider
{
    /**
     * @var
     */
    private $routing_api_version;

    /**
     * @var
     */
    private $route_key;

    /**
     * @var null
     */
    private $proxy;

    /**
     * Базовый url для автозаполнения
     */
    const ROUTE_URL = 'https://api.tomtom.com/routing/{version}/calculateRoute/{way_points}?/json';

    /**
     * Yandex constructor.
     * @param $route_key
     * @param $routing_api_version
     * @param null $proxy
     */
    public function __construct($route_key, $routing_api_version, $proxy = null)
    {
        $this->route_key = $route_key;
        $this->routing_api_version = $routing_api_version;
        $this->proxy = $proxy;
    }

    /**
     * @param RouteQuery $query
     * @return Collection
     */
    public function route(RouteQuery $query): Collection
    {
        try {
            $way_points = $query->getThroughPoints()
                ->transform(function ($coordinate) { return $coordinate->getLatitude() . ',' . $coordinate->getLongitude(); })
                ->implode(':');

            $query = [
                'key' => $this->route_key,
                'traffic' => $query->getTraffic(),
                'language' => $query->getLocale(),
                'travelMode' => $query->getTravelMode(),
                'routeType' => $query->getRouteType()
            ];
            if ($query->getAvoidTollsRoads()) {
                $query['avoid'] = 'tollRoads';
            }

            $response = (new Client())->get(
                str_replace([ '{version}', '{way_points}' ], [ $this->routing_api_version, $way_points ], self::ROUTE_URL),
                [
                    'query' => $query,
                    'proxy' => $this->proxy
                ]
            );
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create('Provider "' . $this->getName() . '" could not build route: "' . $query->__toString() . '".');
        }

        if (!empty($data['errors'])) {
            return collect([]);
        }

        $result = [];
        foreach ($data['route']['legs'] as $leg) {
            foreach ($leg['steps'] as $step) {
                foreach ($step['polyline']['points'] as $point) {
                    $result[] = new Coordinate($point[0], $point[1]);
                }
            }
        }

        return collect($result);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Yandex.ru';
    }

    /**
     * @param RouteQuery $query
     * @return string
     */
    private function mapTravelMode(RouteQuery $query): string
    {
        switch ($query->getTravelMode()) {
            case TravelMode::TRAVEL_MODE_CAR:
                return 'car';
                break;

            case TravelMode::TRAVEL_MODE_TRUCK:
                return 'truck';
                break;
        }
    }
}
