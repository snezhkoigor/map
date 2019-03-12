<?php

namespace Map\Laravel\Dumper;

use Illuminate\Support\Collection;

interface Dumper
{
    /**
     * @param string $name
     * @param Collection $coordinates
     *
     * @return mixed
     */
    public function dumpRoute(string $name, Collection $coordinates);
}