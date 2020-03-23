<?php

namespace Humans\AssetPipeline;

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
            $prefix = config('laravel-asset-pipeline.prefix');

            $router->get(
                "{$prefix}/{asset}",
                '\Humans\AssetPipeline\Controllers\ShowAssetController'
            )->where('asset', '.*');
        });
    }
}
