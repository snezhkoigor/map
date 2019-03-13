<?php

namespace Map\Laravel\Models;

/**
 * Class Url
 * @package Map\Laravel\Models
 */
class Url
{
    /**
     * @var string
     */
    private $url;

    /**
     * Url constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param $name
     * @param $value
     * @return Url
     */
    public function replace($name, $value): self
    {
        $new = clone $this;
        $new->url = str_replace($name, $value, $this->url);

        return $new;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
