<?php

declare(strict_types=1);

if (!defined('APP_DEFINE_GUARD')) die;

// semantics
// - #abc <- this is static, should not be altered by env file
// - FQCN <- as above
// - abc <- this is dynamic (parameter), will definitely be altered by env file
return [
    'api_keys' => [],
    'show_errors' => false,

    '#path.root' => realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR),

    '#configurators' => [
        DI\autowire(\Cvgore\RandomThings\Configurator\GlobalMiddleware::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\Router::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\NotFoundFallbackRouter::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\ErrorHandler::class),
    ],

    '#global_middleware' => [
        DI\autowire(\Cvgore\RandomThings\Middleware\Cors::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\ApiKey::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\JsonBodyParser::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\QueryParamsParser::class),
    ],

    '#controllers' => [
        DI\autowire(\Cvgore\RandomThings\Controller\RandomSalute::class),
        DI\autowire(\Cvgore\RandomThings\Controller\NextEaster::class),
        DI\autowire(\Cvgore\RandomThings\Controller\MorningSalute::class),
        DI\autowire(\Cvgore\RandomThings\Controller\FireFancyText::class),
        DI\autowire(\Cvgore\RandomThings\Controller\EPrescription::class),
        DI\autowire(\Cvgore\RandomThings\Controller\RandomYoutubeVideo::class),
        DI\autowire(\Cvgore\RandomThings\Controller\CalendarDay::class),
        DI\autowire(\Cvgore\RandomThings\Controller\MapaInternetowUnavailableVideos::class),
        DI\autowire(\Cvgore\RandomThings\Controller\ToiletMode::class),
    ],

    '#gif_chain_repositories' => [
        DI\autowire(\Cvgore\RandomThings\Repository\External\GiphyRepository::class),
        DI\autowire(\Cvgore\RandomThings\Repository\External\TenorGifRepository::class),
    ],

    '#fancy_font.generator.fire' => DI\autowire(\Cvgore\RandomThings\Generator\FancyFontGenerator::class)
        ->constructor(DI\get(\Cvgore\RandomThings\FancyFont\FireFancyFontFamily::class)),

    '#cli.commands' => [
        DI\autowire(\Cvgore\RandomThings\Console\Migrate::class),
        DI\autowire(\Cvgore\RandomThings\Console\CheckVideosAvailability::class),
        DI\autowire(\Cvgore\RandomThings\Console\Cron::class),
    ],

    '#cli.cron' => [
        [
            DI\create(\Cron\CronExpression::class)
                ->constructor(DI\get('mapa_internetow.check_videos_availability.cron')),
            'check-videos-availability'
        ]
    ],

    \Symfony\Component\Serializer\SerializerInterface::class =>
        DI\autowire(\Symfony\Component\Serializer\Serializer::class)
            ->constructorParameter('encoders', [
                DI\autowire(\Cvgore\RandomThings\Serializer\QueryStringEncoder::class),
                DI\autowire(\Symfony\Component\Serializer\Encoder\JsonEncoder::class),
            ])
            ->constructorParameter('normalizers', [
                DI\autowire(\Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\DateTimeNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\ArrayDenormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\ObjectNormalizer::class)
                    ->constructorParameter(
                        'propertyTypeExtractor',
                        DI\autowire(\Symfony\Component\PropertyInfo\PropertyInfoExtractor::class)
                            ->constructor([], [
                                DI\autowire(\Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor::class),
                                DI\autowire(\Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor::class),
                            ])
                    ),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\PropertyNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\ProblemNormalizer::class),

            ]),

    \Cvgore\RandomThings\Provider\CurrentDateProvider::class => DI\autowire(),
    \Cvgore\RandomThings\Formatter\NewsFormatter::class => DI\autowire(),
    \Cvgore\RandomThings\Formatter\WeatherPredictionsFormatter::class => DI\autowire(),
    \Cvgore\RandomThings\Http\HttpClient::class => DI\autowire(),
    \Cvgore\RandomThings\Translator\WindSpeedTranslator::class => DI\autowire(),
    \Cvgore\RandomThings\Translator\Translator::class => DI\autowire()->method('init'),
    \Cvgore\RandomThings\Generator\MorningSaluteGenerator::class => DI\autowire(),
    \Cvgore\RandomThings\Generator\FancyFontGenerator::class => DI\autowire(),
    \Cvgore\RandomThings\Generator\ToiletModeTextGenerator::class => DI\autowire(),
    \Cvgore\RandomThings\Generator\EPrescriptionGenerator::class => DI\autowire(),
    \Cvgore\RandomThings\FancyFont\FireFancyFontFamily::class => DI\autowire(),

    \Random\Randomizer::class => DI\factory(function () {
        return new \Random\Randomizer(new \Random\Engine\Secure());
    }),

    \Cvgore\RandomThings\Repository\YoutubeVideosRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\SaluteRepository::class => DI\autowire()->method('init'),

    \Cvgore\RandomThings\Repository\External\GiphyRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\TenorGifRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\NameDaysRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\WeatherForecastRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\MultipleWeatherForecastRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\GifChainRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\NewsRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\YoutubeVideosRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\CalendarRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\OpenAiRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\MapaInternetowRepository::class => DI\decorate(
        function (object $inner) {
            return new \Cvgore\RandomThings\Repository\InMemoryCacheRepository($inner);
        }
    ),

    \SQLite3::class => DI\autowire()
        ->constructor(
            DI\string('{#path.root}/var/db.sqlite3'),
            SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
        )->method(
            'enableExceptions', true
        )
];