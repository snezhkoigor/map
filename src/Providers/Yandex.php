<?php

namespace Map\Laravel\Providers;

use Map\Laravel\Exceptions\InvalidServerResponse;
use Map\Laravel\Models\Query\RouteQuery;
use Map\Laravel\Resources\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Map\Models\Coordinate;

class Yandex implements Provider
{
    /**
     * @var
     */
    private $key;

    /**
     * @var null
     */
    private $proxy;

    /**
     * Базовый url для автозаполнения
     */
    const ROUTE_URL = 'https://api.routing.yandex.net/v1.0.0/route';

    /**
     * Yandex constructor.
     * @param $key
     * @param null $proxy
     */
    public function __construct($key, $proxy = null)
    {
        $this->key = $key;
        $this->proxy = $proxy;
    }

    /**
     * @param RouteQuery $query
     * @return Route|null
     */
    public function route(RouteQuery $query): ?Route
    {
        try {
            $response = (new Client())->get(self::ROUTE_URL, [
                'apikey' => $this->key,
                'waypoints' => $query->getThroughPoints()
                    ->transform(function ($coordinate) {
                        return $coordinate->getLatitude() . ',' . $coordinate->getLongitude();
                    })
                    ->implode('|')
            ]);
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create('Provider "' . $this->getName() . '" could not build route: "' . $query->__toString() . '".');
        }

        if (!empty($response['errors'])) {
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

        return \count($result) ? new Route($this->getName(), $result) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Yandex.ru';
    }
}
