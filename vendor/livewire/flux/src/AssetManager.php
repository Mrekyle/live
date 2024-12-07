<?php

namespace Flux;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;

class AssetManager
{
    static function boot()
    {
        $instance = new static;

        $instance->registerAssetDirective();
        $instance->registerAssetRoutes();
    }

    public function registerAssetDirective()
    {
        Blade::directive('fluxStyles', function ($expression) {
            return <<<'PHP'
            {!! app('flux')->styles() !!}
            PHP;
        });

        Blade::directive('fluxScripts', function ($expression) {
            return <<<'PHP'
            <?php app('livewire')->forceAssetInjection(); ?>
            {!! app('flux')->scripts() !!}
            PHP;
        });
    }

    public function registerAssetRoutes()
    {
        Route::get('/flux/flux.css', function () {
            return Flux::pro()
                ? $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/flux.css', 'text/css')
                : $this->pretendResponseIsFile(__DIR__.'/../../flux/dist/flux-lite.css', 'text/css');
        });

        Route::get('/flux/flux.js', function () {
            return Flux::pro()
                ? $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/flux.js', 'text/javascript')
                : $this->pretendResponseIsFile(__DIR__.'/../../flux/dist/flux-lite.min.js', 'text/javascript');
        });

        Route::get('/flux/flux.min.js', function () {
            return Flux::pro()
                ? $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/flux.min.js', 'text/javascript')
                : $this->pretendResponseIsFile(__DIR__.'/../../flux/dist/flux-lite.min.js', 'text/javascript');
        });

        Route::get('/flux/editor.css', function () {
            if (! Flux::pro()) throw new \Exception('Flux Pro is required to use the Flux editor.');

            return $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/editor.css', 'text/css');
        });

        Route::get('/flux/editor.js', function () {
            if (! Flux::pro()) throw new \Exception('Flux Pro is required to use the Flux editor.');

            return $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/editor.js', 'text/javascript');
        });

        Route::get('/flux/editor.min.js', function () {
            if (! Flux::pro()) throw new \Exception('Flux Pro is required to use the Flux editor.');

            return $this->pretendResponseIsFile(__DIR__.'/../../flux-pro/dist/editor.min.js', 'text/javascript');
        });
    }

    public static function scripts()
    {
        $manifest = Flux::pro()
            ? json_decode(file_get_contents(__DIR__.'/../../flux-pro/dist/manifest.json'), true)
            : json_decode(file_get_contents(__DIR__.'/../../flux/dist/manifest.json'), true);

        $versionHash = $manifest['/flux.js'];

        if (config('app.debug')) {
            return '<script src="/flux/flux.js?id='. $versionHash . '" data-navigate-once></script>';
        } else {
            return '<script src="/flux/flux.min.js?id='. $versionHash . '" data-navigate-once></script>';
        }
    }

    public static function styles()
    {
        $manifest = Flux::pro()
            ? json_decode(file_get_contents(__DIR__.'/../../flux-pro/dist/manifest.json'), true)
            : json_decode(file_get_contents(__DIR__.'/../../flux/dist/manifest.json'), true);

        $versionHash = $manifest['/flux.css'];

        return '<link rel="stylesheet" href="/flux/flux.css?id='. $versionHash . '">';
    }

    public static function editorScripts()
    {
        $manifest = json_decode(file_get_contents(__DIR__.'/../../flux-pro/dist/manifest.json'), true);

        $versionHash = $manifest['/editor.js'];

        if (config('app.debug')) {
            return '<script src="/flux/editor.js?id='. $versionHash . '" defer></script>';
        } else {
            return '<script src="/flux/editor.min.js?id='. $versionHash . '" defer></script>';
        }
    }

    public static function editorStyles()
    {
        $manifest = json_decode(file_get_contents(__DIR__.'/../../flux-pro/dist/manifest.json'), true);

        $versionHash = $manifest['/editor.css'];

        return '<link rel="stylesheet" href="/flux/editor.css?id='. $versionHash . '">';
    }

    public function pretendResponseIsFile($file, $contentType = 'application/javascript; charset=utf-8')
    {
        $lastModified = filemtime($file);

        return $this->cachedFileResponse($file, $contentType, $lastModified,
            fn ($headers) => response()->file($file, $headers));
    }

    protected function cachedFileResponse($filename, $contentType, $lastModified, $downloadCallback)
    {
        $expires = strtotime('+1 year');
        $cacheControl = 'public, max-age=31536000';

        if ($this->matchesCache($lastModified)) {
            return response('', 304, [
                'Expires' => $this->httpDate($expires),
                'Cache-Control' => $cacheControl,
            ]);
        }

        $headers = [
            'Content-Type' => $contentType,
            'Expires' => $this->httpDate($expires),
            'Cache-Control' => $cacheControl,
            'Last-Modified' => $this->httpDate($lastModified),
        ];

        if (str($filename)->endsWith('.br')) {
            $headers['Content-Encoding'] = 'br';
        }

        return $downloadCallback($headers);
    }

    protected function matchesCache($lastModified)
    {
        $ifModifiedSince = app(Request::class)->header('if-modified-since');

        return $ifModifiedSince !== null && @strtotime($ifModifiedSince) === $lastModified;
    }

    protected function httpDate($timestamp)
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}