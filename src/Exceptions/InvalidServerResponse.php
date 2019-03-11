<?php

declare(strict_types=1);

namespace Map\Laravel\Exceptions;

use Map\Laravel\Exception\Exception;

/**
 * Class InvalidServerResponse
 * @package Map\Laravel\Exceptions
 */
final class InvalidServerResponse extends \RuntimeException implements Exception
{
    /**
     * @param string $query
     * @param int    $code
     *
     * @return InvalidServerResponse
     */
    public static function create(string $query, int $code = 0): self
    {
        return new self(sprintf('The map server returned an invalid response (%d) for query "%s". We could not parse it.', $code, $query));
    }

    /**
     * @param string $query
     *
     * @return InvalidServerResponse
     */
    public static function emptyResponse(string $query): self
    {
        return new self(sprintf('The map server returned an empty response for query "%s".', $query));
    }
}
