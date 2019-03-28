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
     * @var string
     */
    private $proxy;

    /**
     * @var int
     */
    private $proxy_port;

    /**
     * Базовый url для автозаполнения
     */
    const ROUTE_URL = 'https://api.tomtom.com/routing/{version}/calculateRoute/{way_points}/json';

    /**
     * Yandex constructor.
     * @param $route_key
     * @param $routing_api_version
     * @param null $proxy
     * @param int $proxy_port
     */
    public function __construct($route_key, $routing_api_version, $proxy = null, $proxy_port = 80)
    {
        $this->route_key = $route_key;
        $this->routing_api_version = $routing_api_version;
        $this->proxy = $proxy;
        $this->proxy_port = $proxy_port;
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
                ->implode(':');

            $request_data = [
                'key' => $this->route_key,
                'traffic' => json_encode($query->getTraffic()),
                'language' => $query->getLocale(),
                'travelMode' => $this->mapTravelMode($query),
                'routeType' => $query->getRouteType()
            ];
            if ($query->getAvoidTollsRoads()) {
                $request_data['avoid'] = 'tollRoads';
            }

            $response = (new Client())->get(
                (new Url(self::ROUTE_URL))->replace('{version}', $this->routing_api_version)->replace('{way_points}', $way_points)->getUrl(),
                [
                    'query' => $request_data,
                    'proxy' => !empty($this->proxy) ? $this->proxy . ':' . $this->proxy_port : null
                ]
            );
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create('Provider "' . $this->getName() . '" could not build route: "' . $query->__toString() . '".');
        }

        if (!empty($data['error']) || empty($data['routes'][0]['legs'])) {
            return null;
        }

        $result = [];
        foreach ($data['routes'][0]['legs'] as $leg) {
            foreach ($leg['points'] as $point) {
                $result[] = new Coordinate($point['latitude'], $point['longitude']);
            }
        }

        return new Route($this->getName(), $result);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'TomTom.com';
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
