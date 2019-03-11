<?php

declare(strict_types=1);

namespace Map\Models;

use Map\Laravel\Exceptions\InvalidArgument;

/**
 * Class Coordinates
 * @package Map\Models
 */
final class Coordinate
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        if ($latitude === null) {
            throw new InvalidArgument('Value cannot be null');
        }
        if ($longitude === null) {
            throw new InvalidArgument('Value cannot be null');
        }

        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidArgument(sprintf('Latitude should be between -90 and 90. Got: %s', $latitude));
        }
        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidArgument(sprintf('Longitude should be between -180 and 180. Got: %s', $longitude));
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude.
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Returns the longitude.
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Returns the coordinates as a tuple
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getLongitude(),
            $this->getLatitude()
        ];
    }
}