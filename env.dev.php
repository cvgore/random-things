<?php

if (!defined('APP_CTX')) die;

return [
    'env' => 'dev',
    'api_keys' => [
        //
    ],
    'show_errors' => true,
    'giphy.url' => 'https://api.giphy.com',
    'giphy.api_key' => '',
    'namedays.url' => 'https://nameday.abalin.net',
    'weather_api.url' => 'https://api.met.no',
    'weather_api.location' => [
        'lat' => '',
        'lng' => ''
    ]
];