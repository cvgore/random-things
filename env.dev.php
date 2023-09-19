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
    'giphy.default_gif_url' => 'https://giphy.com/embed/sgxdxAK44EXcI',
    'tenor_gif.url' => 'https://tenor.googleapis.com',
    'tenor_gif.api_key' => '',
    'tenor_gif.client_key' => '',
    'tenor_gif.default_gif_url' => 'https://tenor.com/bCZNb.gif',
    'tenor_gif.locale' => 'pl',
    'tenor_gif.country' => 'PL',
    'namedays.url' => 'https://nameday.abalin.net',
    'weather_api.url' => 'https://api.met.no',
    'weather_api.location' => [
        'lat' => '',
        'lng' => ''
    ]
];
