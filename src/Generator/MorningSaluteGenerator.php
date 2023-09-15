<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Provider\CurrentDateProvider;
use Cvgore\RandomThings\Repository\External\NameDaysRepository;
use Cvgore\RandomThings\Repository\External\WeatherForecastRepository;
use DI\Attribute\Inject;

final readonly class MorningSaluteGenerator
{
	#[Inject]
	private NameDaysRepository $nameDaysRepository;

	#[Inject]
	private CurrentDateProvider $currentDateProvider;

	#[Inject]
	private WeatherForecastRepository $weatherForecastRepository;

	#[Inject]
	private PathGenerator $pathGenerator;

	public function generate(): string
	{
		$template = file_get_contents($this->pathGenerator->getResourcePath('morning-salute.tpl'));

		$nameDays = $this->nameDaysRepository->getNameDaysForToday();
		$nameDays = $nameDays
			? implode(',', $nameDays)
			: '<niewiadomo kto>';

		$weatherPrediction = $this->weatherForecastRepository->getForecastForToday();
		if (! $weatherPrediction) {
			$weatherPrediction = '<brak danych>';
		} else {
			$weatherPrediction = sprintf(
				'%s %.1f°C %.1fm/s',
				$weatherPrediction->briefDescription,
				$weatherPrediction->temperature,
				$weatherPrediction->windSpeed,
			);
		}

		return str_replace(
			['@today', '@nameDays', '@weatherPrediction'],
			[$this->currentDateProvider->todayLong(), $nameDays, $weatherPrediction],
			$template
		);
	}
}
