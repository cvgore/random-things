<?php

if (!defined('APP_CTX')) die;

return [
    'env' => 'prod',
    'api_keys' => [
        //
    ],
    'show_errors' => false,
    'giphy.url' => 'https://api.giphy.com',
    'giphy.api_key' => '',
    'giphy.default_gif_url' => 'https://giphy.com/embed/sgxdxAK44EXcI',
    'namedays.url' => 'https://nameday.abalin.net',
    'weather_api.url' => 'https://api.met.no',
    'weather_api.location' => [
        'lat' => '',
        'lng' => ''
    ]
];
