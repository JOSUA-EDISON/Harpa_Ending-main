<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration for RajaOngkir API
    |
    */

    'api_key' => env('RAJAONGKIR_API_KEY', ''),
    'package' => env('RAJAONGKIR_PACKAGE', 'starter'),

    /*
    |--------------------------------------------------------------------------
    | Default Shipping Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for shipping
    |
    */

    'jnt' => [
        'sender' => [
            'city' => 151, // Default sender city (Jakarta Pusat)
            'address' => 'Jl. Kebon Sirih No. 1, Jakarta Pusat'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Package Weight (in grams)
    |--------------------------------------------------------------------------
    */
    'min_weight' => 1, // Minimum weight in grams
    'max_weight' => 30000, // Maximum weight in grams (30kg)
];
