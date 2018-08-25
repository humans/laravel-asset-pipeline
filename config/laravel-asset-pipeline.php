<?php

return [
    'path' => resource_path('assets'),

    /**
     * This can take in different types of callables.
     *
     * [$object, 'method']
     * function ($request, $asset) { }
     * SomeClass::class with a `handle($request, $asset)``
     */
    'pipes' => [
    ],
];
