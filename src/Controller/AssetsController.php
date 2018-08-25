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

        return response()->file($asset);
    }

    private function asset($asset)
    {
        return str_finish(config('laravel-asset-pipeline.path'), '/') . $asset;
    }
}
