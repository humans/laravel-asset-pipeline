<?php

namespace Artisan\AssetPipeline;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfig();

        $this->registerRoutesMacro();
    }

    public function boot()
    {
        $this->publishConfig();
    }

    protected function mergeConfig()
    {
        $configPath = __DIR__ . '/../config/laravel-asset-pipeline.php';

        $this->mergeConfigFrom($configPath, 'laravel-asset-pipeline');
    }

    protected function publishConfig()
    {
        $configPath = __DIR__ . '/../config/laravel-asset-pipeline.php';

        $this->publishes([
            $configPath => config_path('laravel-asset-pipeline.php'),
        ], 'asset-pipeline');
    }

    protected function registerRoutesMacro()
    {
        $router = $this->app->router;

        $router->macro('assetPipeline', function () use ($router) {
            $router->get(
                'assets/{asset}',
                '\Artisan\AssetPipeline\Controllers\ShowAssetController'
            )->where('asset', '.*');
        });
    }
}
