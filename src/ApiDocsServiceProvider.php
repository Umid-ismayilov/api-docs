<?php

namespace Br\ApiDocsPackage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class ApiDocsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/api-docs.php', 'api-docs');
    }

    public function boot()
    {
        if (config('api-docs.enabled')) {
            $this->loadViewsFrom(__DIR__ . '/views', 'api-docs');
            $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

            $this->publishes([
                __DIR__ . '/config/api-docs.php' => config_path('api-docs.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/views' => resource_path('views/vendor/api-docs'),
            ], 'views');

            $this->registerRoutes();
            $this->registerApiLogger();
        }
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        });
    }

    protected function routeConfiguration()
    {

        $config = [
            'prefix' => config('api-docs.route_prefix'),
        ];

        $middleware = config('api-docs.middleware');
        if (!empty($middleware)) {
            $config['middleware'] = $middleware;
        }

        return $config;
    }

    protected function registerApiLogger()
    {
        $this->app->booted(function () {
            $docs = DB::table('api_docs')->where('route', $this->app->request->fullUrl())->first();
            $headers = Request::header();
            $body = Request::getContent();

            if (!$docs) {
                $ipPrefix = config('api-docs.ip_prefix');
                $apiPrefix = config('api-docs.api_prefix');

                if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) &&
                    $_SERVER['HTTP_CF_CONNECTING_IP'] === $ipPrefix &&
                    $this->app->request->is($apiPrefix)) {
                    DB::table('api_docs')->updateOrInsert(
                        [
                            'route' => $this->app->request->fullUrl(),
                            'method' => Request::method()
                        ],
                        [
                            'header' => json_encode($headers),
                            'body' => json_encode($body)
                        ]
                    );
                }
            }
        });
    }
}