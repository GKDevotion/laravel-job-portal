<?php

use Illuminate\Support\Str;

return [

    'default' => env('FILESYSTEM_DISK', 'public'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        // Default disk for logos, avatars, resumes when no cloud storage is configured.
        // Publicly accessible via /storage (php artisan storage:link).
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // Amazon S3. Also doubles as the DigitalOcean Spaces disk — Spaces is
        // S3-API-compatible, so setting AWS_ENDPOINT to your Spaces region endpoint
        // (e.g. https://nyc3.digitaloceanspaces.com) is the only difference. No code
        // changes needed to move between the two — just .env values.
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'), // set this for DigitalOcean Spaces, leave blank for real S3
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
