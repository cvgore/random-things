<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Formatter;

use Cvgore\RandomThings\Dto\WeatherForecast;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class WeatherPredictionsFormatter
{
	#[Inject]
	private Translator $translator;

	/**
	 * @param array<string, WeatherForecast> $forecasts
	 */
	public function format(array $forecasts): string
	{
		if (count($forecasts) === 0) {
			return $this->translator->translate('weather.no-data');
		}

		$forecasts = array_filter($forecasts);

		$result = array_map(
			$this->formatSingle(...),
			array_keys($forecasts),
			$forecasts
		);
		return implode("\n", $result);
	}

	private function formatSingle(string $name, WeatherForecast $forecast): string
	{
		return sprintf(
			'- **%s**: %s 🌡️ %.1f°C 💨 %s (%.1fm/s)',
			$name,
			$forecast->briefDescription,
			$forecast->temperature,
			$forecast->windSpeedDescription,
			$forecast->windSpeed,
		);
	}
}
