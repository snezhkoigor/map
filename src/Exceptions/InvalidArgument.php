<?php

declare(strict_types=1);

namespace Map\Laravel\Exceptions;

use Map\Laravel\Exception\Exception;

/**
 * Class InvalidArgument
 * @package Map\Laravel\Exceptions
 */
class InvalidArgument extends \InvalidArgumentException implements Exception {}