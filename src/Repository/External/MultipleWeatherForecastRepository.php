<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\WeatherForecast;
use DI\Attribute\Inject;

final readonly class MultipleWeatherForecastRepository
{
	#[Inject(name: 'weather_api.locations')]
	private array $locations;

	#[Inject(name: 'weather_api.throttle_ms')]
	private int $throttleMs;

	#[Inject]
	private WeatherForecastRepository $repository;

	/**
	 * @return array<string, WeatherForecast>
	 */
	public function getForecastForToday(): array
	{
		$forecasts = [];
		$throttleMs = min(0, $this->throttleMs);

		foreach ($this->locations as $name => $location) {
			$forecasts[$name] = $this->repository->getForecastForToday(
				$location['lat'],
				$location['lng']
			);
			usleep($throttleMs * 1000);
		}

		return array_filter($forecasts);
	}
}
