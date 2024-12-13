<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

use Cvgore\RandomThings\Formatter\WeatherPredictionsFormatter;
use Cvgore\RandomThings\Repository\External\MultipleWeatherForecastRepository;
use DI\Attribute\Inject;

final readonly class WeatherPredictionsProcessor implements MorningSaluteProcessor
{
    #[Inject]
    private MultipleWeatherForecastRepository $weatherForecastRepository;

    #[Inject]
    private WeatherPredictionsFormatter $weatherPredictionsFormatter;

    public function getPlaceholder(): string
    {
        return '@weatherPredictions';
    }

    public function generate(): string
    {
        $weatherPredictions = $this->weatherForecastRepository->getForecastForToday();

        return $this->weatherPredictionsFormatter->format(
            $weatherPredictions
        );
    }
}