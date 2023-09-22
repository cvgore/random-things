<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Formatter\NewsFormatter;
use Cvgore\RandomThings\Formatter\WeatherPredictionsFormatter;
use Cvgore\RandomThings\Provider\CurrentDateProvider;
use Cvgore\RandomThings\Repository\External\MultipleWeatherForecastRepository;
use Cvgore\RandomThings\Repository\External\NameDaysRepository;
use Cvgore\RandomThings\Repository\External\NewsRepository;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class MorningSaluteGenerator
{
	#[Inject]
	private NameDaysRepository $nameDaysRepository;

	#[Inject]
	private CurrentDateProvider $currentDateProvider;

	#[Inject]
	private MultipleWeatherForecastRepository $weatherForecastRepository;

	#[Inject]
	private WeatherPredictionsFormatter $weatherPredictionsFormatter;

	#[Inject]
	private NewsRepository $newsRepository;

	#[Inject]
	private NewsFormatter $newsFormatter;

	#[Inject]
	private PathGenerator $pathGenerator;

	#[Inject]
	private Translator $translator;

	public function generate(): string
	{
		$lang = $this->translator->getCurrentLanguage();

		$template = file_get_contents(
			$this->pathGenerator->getResourcePath("morning-salute-{$lang}.tpl")
		);

		$nameDays = $this->nameDaysRepository->getRandomNameDaysForToday();
		$nameDays = $nameDays
			? implode(',', $nameDays)
			: $this->translator->translate('namedays.no-data');

		$weatherPredictions = $this->weatherForecastRepository->getForecastForToday();
		$weatherPredictions = $this->weatherPredictionsFormatter->format(
			$weatherPredictions
		);

		$news = $this->newsRepository->getRandomTopNews();
		$news = $this->newsFormatter->format($news);

		return str_replace(
			['@today', '@nameDays', '@weatherPredictions', '@news'],
			[
				$this->currentDateProvider->todayLong(),
				$nameDays,
				$weatherPredictions,
				$news,
			],
			$template
		);
	}
}
