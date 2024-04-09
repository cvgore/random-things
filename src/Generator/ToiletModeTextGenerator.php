<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Formatter\NewsFormatter;
use Cvgore\RandomThings\Formatter\WeatherPredictionsFormatter;
use Cvgore\RandomThings\Provider\CurrentDateProvider;
use Cvgore\RandomThings\Repository\External\CalendarRepository;
use Cvgore\RandomThings\Repository\External\MultipleWeatherForecastRepository;
use Cvgore\RandomThings\Repository\External\NameDaysRepository;
use Cvgore\RandomThings\Repository\External\NewsRepository;
use Cvgore\RandomThings\Repository\External\OpenAiRepository;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class ToiletModeTextGenerator
{
	#[Inject]
	private OpenAiRepository $openAiRepository;

	#[Inject]
	private PathGenerator $pathGenerator;

	public function generate(): string
	{
        $prompt = file_get_contents(
            $this->pathGenerator->getResourcePath("toilet-mode.prompt")
        );

        $defaultText = file_get_contents(
            $this->pathGenerator->getResourcePath("toilet-mode.default")
        );

        return $this->openAiRepository->generateText($prompt) ?? $defaultText;
	}
}
