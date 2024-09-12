<?php

namespace Br\ApiDocs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class ApiDocsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/api-docs.php', 'api-docs');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'api-docs');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/api-docs.php' => config_path('api-docs.php'),
        ], 'config');

        $this->registerRoutes();
        $this->registerApiLogger();
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            Route::get('/api-docs', [ApiDocsController::class, 'index'])->name('api-docs.index');
            Route::get('/api-docs/{id}', [ApiDocsController::class, 'show'])->name('api-docs.show');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('api-docs.route_prefix'),
            'middleware' => config('api-docs.middleware'),
        ];
    }

    protected function registerApiLogger()
    {
        $this->app->booted(function () {
            if (!$this->checkIfTableExists('api_docs')) {
                $this->createApiDocsTable();
            }

            try {
                $docs = DB::table('api_docs')->where('route', $this->app->request->fullUrl())->first();
                $headers = Request::header();
                $body = Request::getContent();

                $ipPrefix = config('api-docs.ip_prefix');
                $apiPrefix = config('api-docs.api_prefix');

                if (!$docs &&
                    isset($_SERVER['HTTP_CF_CONNECTING_IP']) &&
                    $_SERVER['HTTP_CF_CONNECTING_IP'] === $ipPrefix &&
                    $this->app->request->is($apiPrefix)) {
                    DB::table('api_docs')->updateOrInsert(
                        [
                            'route' => $this->app->request->fullUrl(),
                            'method' => Request::method()
                        ],
                        [
                            'headers' => json_encode($headers),
                            'body' => $body
                        ]
                    );
                }
            } catch (QueryException $e) {
                // Log the error or handle it as needed
                // For now, we'll just suppress it to avoid breaking the application
            }
        });
    }

    protected function checkIfTableExists($table)
    {
        try {
            return Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function createApiDocsTable()
    {
        if (!$this->checkIfTableExists('api_docs')) {
            Schema::create('api_docs', function ($table) {
                $table->id();
                $table->string('route');
                $table->string('method');
                $table->json('headers')->nullable();
                $table->text('body')->nullable();
                $table->timestamps();
            });
        }
    }
}