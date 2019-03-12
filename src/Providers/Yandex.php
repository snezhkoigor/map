<?php

namespace Map\Laravel\Providers;

use Map\Laravel\Exceptions\InvalidServerResponse;
use Map\Laravel\Models\Query\RouteQuery;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Map\Laravel\Models\Coordinate;

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
     * @return Collection
     */
    public function route(RouteQuery $query): Collection
    {
        try {
            $way_points = $query->getThroughPoints()
                ->transform(function ($coordinate) { return $coordinate->getLatitude() . ',' . $coordinate->getLongitude(); })
                ->implode('|');

            $response = (new Client())->get(self::ROUTE_URL, [
                'query' => [
                    'apikey' => $this->key,
                    'waypoints' => $way_points
                ]
            ]);
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
}
