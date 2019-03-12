<?php

namespace Map\Laravel\Dumper;

use Illuminate\Support\Collection;

interface Dumper
{
    /**
     * Dumps an `Location` object as a string representation of
     * the implemented format.
     *
     * @param string $name
     * @param Collection $coordinates
     *
     * @return mixed
     */
    public function dumpRoute(string $name, Collection $coordinates);
}