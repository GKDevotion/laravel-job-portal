<?php

return [

    // Media library uses whichever disk FILESYSTEM_DISK points to — so switching the whole
    // app from local storage to S3/Spaces is a single .env change (FILESYSTEM_DISK=s3),
    // no code or migration needed. Existing files on the old disk are NOT auto-migrated;
    // see the README section on switching storage backends for the migration command.
    'disk_name' => env('MEDIA_DISK', env('FILESYSTEM_DISK', 'public')),

    'max_file_size' => 1024 * 1024 * 10, // 10MB default cap; overridden per-collection below

    'queue_conversions_by_default' => false, // keep synchronous for simplicity; flip true once a queue worker is running

    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    'temporary_upload_media_model' => Spatie\MediaLibrary\MediaCollections\Models\TemporaryUpload::class,

    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    'file_namer' => App\Support\FileNaming\SanitizedFileNamer::class,

    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    'moves_media_on_update' => false,

    'version_urls' => false,

    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    'ffmpeg_binaries' => env('FFMPEG_BINARIES', '/usr/bin/ffmpeg'),
    'ffprobe_binaries' => env('FFPROBE_BINARIES', '/usr/bin/ffprobe'),

    'temporary_url_default_lifetime' => 30,

    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
    ],

    'image_optimizers' => [],

    'temporary_directory_path' => null,

    'default_loading_attribute_value' => null,

    'prefix' => env('MEDIA_PREFIX', ''),

    'enable_vapor_uploads' => env('MEDIA_LIBRARY_ENABLE_VAPOR_UPLOADS', false),

    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800', // 7 days — logos/avatars change rarely
        ],
    ],

    'responsive_images' => [
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,
        'use_tiny_placeholders' => true,
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],
];
