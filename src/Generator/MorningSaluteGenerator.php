<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Formatter\NewsFormatter;
use Cvgore\RandomThings\Formatter\WeatherPredictionsFormatter;
use Cvgore\RandomThings\Processors\MorningSalute\MorningSaluteProcessor;
use Cvgore\RandomThings\Provider\CurrentDateProvider;
use Cvgore\RandomThings\Repository\External\CalendarRepository;
use Cvgore\RandomThings\Repository\External\MultipleWeatherForecastRepository;
use Cvgore\RandomThings\Repository\External\NameDaysRepository;
use Cvgore\RandomThings\Repository\External\NewsRepository;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class MorningSaluteGenerator
{
	#[Inject]
	private PathGenerator $pathGenerator;

    #[Inject]
    private Translator $translator;

    /**
     * @var MorningSaluteProcessor[]
     */
    #[Inject(name: '#processors.morning_salute')]
    private array $processors;

	public function generate(): string
	{
		$lang = $this->translator->getCurrentLanguage();

		$template = file_get_contents(
			$this->pathGenerator->getResourcePath("morning-salute-{$lang}.tpl")
		);

        $placeholders = array_map(
            fn(MorningSaluteProcessor $processor) => $processor->getPlaceholder(), $this->processors
        );
        $replacements = array_map(
            fn(MorningSaluteProcessor $processor) => $processor->generate(), $this->processors
        );

		return str_replace($placeholders, $replacements, $template);
	}
}
