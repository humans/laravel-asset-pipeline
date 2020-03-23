# Laravel Asset Pipeline

This is a Laravel package that provides a pipeline for assets.

## Installation

```
composer require humans/laravel-asset-pipeline
```

## Configuration

Let's publish the default config so we can transform our assets.

```
php artisan vendor:publish --tags=asset-pipeline
```

We'll get a config file in `config/laravel-asset-pipeline.php`.

```php
<?php

return [
    'path' => resource_path('assets'),

    'pipeline' => [
    ],
];
```

The `pipes` can take in different formats of callables.

```php
<?php

return [
    'pipeline' => [
        // For quick or small manpulations!
        function ($request, $asset) {
            return $asset;
        },

        // I won't particularly use this but it's there!
        [$object, 'method'],


        // This needs to have a `handle($request, $asset)` method.
        SomeClass::class,
    ],
];
```
