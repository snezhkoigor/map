<?php

namespace Map\Laravel\Providers;

use Map\Laravel\Exceptions\InvalidServerResponse;
use Map\Laravel\Models\Query\RouteQuery;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Map\Laravel\Models\Coordinate;
use Map\Laravel\Models\TravelMode;
use Map\Laravel\Models\Url;
use Map\Laravel\Resources\Route;

/**
 * Class Yandex
 * @package Map\Laravel\Providers
 */
class Yandex implements Provider
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
    const ROUTE_URL = 'https://api.routing.yandex.net/{version}/route';

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
     * @return null|Route
     */
    public function route(RouteQuery $query): ?Route
    {
        if ($query->getThroughPoints()->count() < 2) {
            return null;
        }

        try {
            $way_points = $query->getThroughPoints()
                ->transform(function ($coordinate) { return $coordinate->getLatitude() . ',' . $coordinate->getLongitude(); })
                ->implode('|');

            $response = (new Client())->get(
                (new Url(self::ROUTE_URL))->replace('{version}', $this->routing_api_version)->getUrl(),
                [
                    'query' => [
                        'apikey' => $this->route_key,
                        'waypoints' => $way_points,
                        'mode' => $this->mapTravelMode($query),
                        'avoid_tolls' => $query->getAvoidTollsRoads()
                    ],
                    'proxy' => $this->proxy
                ]
            );
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create('Provider "' . $this->getName() . '" could not build route: "' . $query->__toString() . '".');
        }

        if (!empty($data['errors']) || empty($data['route']['legs'])) {
            return null;
        }

        $result = [];
        foreach ($data['route']['legs'] as $leg) {
            foreach ($leg['steps'] as $step) {
                foreach ($step['polyline']['points'] as $point) {
                    $result[] = new Coordinate($point[0], $point[1]);
                }
            }
        }

        return new Route($this->getName(), $result);
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
            case TravelMode::TRAVEL_MODE_TRUCK:
                return 'driving';
                break;
        }
    }
}
