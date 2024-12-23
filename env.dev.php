<?php

if (!defined('APP_DEFINE_GUARD')) die;

return [
    'env' => 'dev',
    'api_keys' => [
        //
    ],
    'show_errors' => true,
    'lang' => 'pl',

    'morning_salute.gif_tag' => 'cat xd',

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
    'namedays.country' => 'pl',
    'namedays.timezone' => 'Europe/Warsaw',
    'namedays.limit' => 3,

    'news_api.url' => 'https://newsdata.io',
    'news_api.api_key' => '',
    'news_api.domains_whitelist' => '',
    'news_api.domain_priority' => 'medium',
    'news_api.category' => 'top',
    'news_api.language' => 'pl',
    'news_api.query' => '',
    'news_api.limit' => 4,

    'weather_api.url' => 'https://api.met.no',
    'weather_api.throttle_ms' => 500,
    'weather_api.locations' => [
    ],

    'fancy_font.family.fire.max_len' => 40,

    'eprescription.item_name.max_len' => 36,
    'eprescription.issued_by.max_len' => 28,
    'eprescription.patient_name.max_len' => 28,
    'eprescription.dose_text.max_len' => 45,
    'eprescription.code.length' => 4,
    'eprescription.issued_by.default_value' => 'Apteka Max Pain',
    'eprescription.dose_text.default_value' => '1 op. po 6 tabl.',

    'random_yt_movie.tries' => 5,
    'random_yt_movie.url' => 'https://www.youtube.com/oembed',

    'calendar.url' => 'https://pniedzwiedzinski.github.io/kalendarz-swiat-nietypowych/',

    'mapa_internetow.url' => 'https://raw.githubusercontent.com/polskie-mapy/data/master/data.json',
    'mapa_internetow.check_videos_availability.cron' => '0 0 * * 1',

    'openai.url' => 'https://api.openai.com',
    'openai.api_key' => '',

    'ovh.front.url' => 'https://www.ovh.com/engine/apiv6',
    'ovh.check_servers_availability.cron' => '*/15 * * * *',

    'discord.url' => 'https://discord.com/api/v10',
    'discord.api_key' => '',

    'notifications.discord.user_id' => '',

    'sentry.dsn' => '',

    'subs_reminder.url' => '',

    'currency_api.url' => 'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies'
];
