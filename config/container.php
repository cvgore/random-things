<?php

declare(strict_types=1);

if (!defined('APP_CTX')) die;

return [
    'api_keys' => [],
    'show_errors' => false,
    'path.root' => __DIR__ . '/../',

    'configurators' => [
        DI\autowire(\Cvgore\RandomThings\Configurator\GlobalMiddleware::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\Router::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\NotFoundFallbackRouter::class),
        DI\autowire(\Cvgore\RandomThings\Configurator\ErrorHandler::class),
    ],

    'global_middleware' => [
        DI\autowire(\Cvgore\RandomThings\Middleware\Cors::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\ApiKey::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\JsonBodyParser::class),
        DI\autowire(\Cvgore\RandomThings\Middleware\QueryParamsParser::class),
    ],

    'controllers' => [
        DI\autowire(\Cvgore\RandomThings\Controller\RandomSalute::class),
        DI\autowire(\Cvgore\RandomThings\Controller\NextEaster::class),
        DI\autowire(\Cvgore\RandomThings\Controller\MorningSalute::class),
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
                DI\autowire(\Symfony\Component\Serializer\Normalizer\ObjectNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\PropertyNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer::class),
                DI\autowire(\Symfony\Component\Serializer\Normalizer\ProblemNormalizer::class),
            ]),

    \Cvgore\RandomThings\Provider\CurrentDateProvider::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\SaluteRepository::class => DI\autowire()->method('init'),
    \Cvgore\RandomThings\Http\HttpClient::class => DI\autowire(),
    \Cvgore\RandomThings\Generator\MorningSaluteGenerator::class => DI\autowire(),
    \Random\Randomizer::class => DI\autowire(),
    \Cvgore\RandomThings\Translator\WeatherSymbolTranslator::class => DI\autowire()->method('init'),

    \Cvgore\RandomThings\Repository\External\GiphyRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\NameDaysRepository::class => DI\autowire(),
    \Cvgore\RandomThings\Repository\External\WeatherForecastRepository::class => DI\autowire(),
];