<?php

namespace Map\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class RouteType extends Model
{
    const ROUTE_TYPE_FASTEST = 'fastest';

    const ROUTE_TYPE_SHORTEST = 'shortest';

    const ROUTE_TYPE_ECO = 'eco';
}
