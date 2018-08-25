<?php

namespace Artisan\AssetPipeline\Controllers;

class AssetsController extends \Illuminate\Routing\Controller
{
    public function show($asset = null)
    {
        $asset = $this->asset($asset);

        if (! file_exists($asset)) {
            abort(404);
        }

        return response()->file(
            $this->transform($asset)
        );
    }

    private function asset($asset)
    {
        return str_finish(config('laravel-asset-pipeline.path'), '/') . $asset;
    }

    private function transform($asset)
    {
        foreach(config('laravel-asset-pipeline.pipes') as $pipe) {
            $callable = $pipe;

            if (is_string($callable)) {
                $callable = [new $callable, 'handle'];
            }

            $asset = call_user_func($callable, request(), $asset);
        }

        return $asset;
    }
}
