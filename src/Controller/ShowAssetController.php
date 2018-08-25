<?php

namespace Artisan\AssetPipeline\Controllers;

class ShowAssetController extends \Illuminate\Routing\Controller
{
    /**
     * Find the asset on the asset path and run it through the
     * asset pipeline.
     *
     * @param  string  $asset
     * @return \Illuminate\Http\Response
     */
    public function __invoke($asset)
    {
        $asset = $this->asset($asset);

        if (! file_exists($asset)) {
            abort(404);
        }

        if (! file_exists($asset = $this->transform($asset))) {
            abort(404);
        }

        return response()->file($asset);
    }

    /**
     * Get the asset's path based from the config set.
     *
     * @param  string  $asset
     * @return string
     */
    private function asset($asset)
    {
        return str_finish(config('laravel-asset-pipeline.path'), '/') . $asset;
    }

    /**
     * Run the file through the asset pipeline.
     *
     * I honestly don't even know if my terminology is even correct
     * but I'd rather finish it with the functionality I want with
     * the incorrect label and correct it later.
     *
     * @param  string  $asset
     * @return string
     */
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
