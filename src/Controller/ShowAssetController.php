<?php

namespace Humans\AssetPipeline\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
        $filesystem = Storage::disk(Config::get('laravel-asset-pipeline.disk'));

        if (! $filesystem->exists($asset)) {
            abort(404);
        }

        $asset = $this->transform($asset);

        return Response::make(Cache::rememberForever($asset, function () use ($filesystem, $asset) {
            return $filesystem->get($asset);
        }), $status = 200, [
            'Content-Type' => 'image/' . pathinfo($asset, PATHINFO_EXTENSION),
        ]);
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
        foreach(Config::get('laravel-asset-pipeline.pipeline') as $pipe) {
            $callable = $pipe;

            if (is_string($callable)) {
                $callable = [App::make($callable), 'handle'];
            }

            $asset = call_user_func($callable, request(), $asset);
        }

        return $asset;
    }
}
