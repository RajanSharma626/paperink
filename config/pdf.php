<?php

// config/pdf.php - Create this new config file
return [
    /*
    |--------------------------------------------------------------------------
    | PDF Generation Settings
    |--------------------------------------------------------------------------
    */

    'generator' => [
        'binary_path' => env('PDF_GENERATOR_PATH', base_path('bin/pdf-generator')),
        'temp_path' => env('PDF_TEMP_PATH', storage_path('app/temp')),
        'timeout' => env('PDF_GENERATION_TIMEOUT', 30), // seconds
    ],

    'chrome' => [
        'path' => env('CHROME_PATH', '/usr/bin/chromium-browser'),
    ],

    'defaults' => [
        'format' => 'A4',
        'margin' => '0.5in',
        'print_background' => true,
    ],
];

// Also add these to your .env file:
/*
PDF_GENERATOR_PATH=/path/to/your/laravel/bin/pdf-generator
PDF_TEMP_PATH=/path/to/your/laravel/storage/app/temp
PDF_GENERATION_TIMEOUT=30
CHROME_PATH=/usr/bin/chromium-browser
*/