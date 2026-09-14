<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;

class CheckForMaintenanceMode extends PreventRequestsDuringMaintenance
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/admin*',
        '/login',
        '/logout',
        '/subcategories*',
        '/subsubcategories*',
        '/home_categories*',
        '/aiz-uploader*',
    ];
}
