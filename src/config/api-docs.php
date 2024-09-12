<?php

return [
    'ip_prefix'    => env('API_DOCS_IP_PREFIX', ''),
    'api_prefix'   => env('API_DOCS_API_PREFIX', 'api/*'),
    'layout'       => env('API_DOCS_LAYOUT', ''),
    'route_prefix' => env('API_DOCS_ROUTE_PREFIX', 'api-docs'),
    'middleware'   => env('API_DOCS_MIDDLEWARE', 'auth'),
    'enabled'      => env('API_DOCS_ENABLED', true),
];