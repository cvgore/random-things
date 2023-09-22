<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Extractor;

class WeatherForecastExtractor
{
	public function getSymbolCode(array $forecast): ?string
	{
		if (array_key_exists('next_1_hours', $forecast)) {
			$source = $forecast['next_1_hours'];
		} elseif (array_key_exists('next_6_hours', $forecast)) {
			$source = $forecast['next_6_hours'];
		} elseif (array_key_exists('next_12_hours', $forecast)) {
			$source = $forecast['next_12_hours'];
		} else {
			return null;
		}

		assert(array_key_exists('summary', $source));
		assert(array_key_exists('symbol_code', $source['summary']));

		return $source['summary']['symbol_code'];
	}

	public function getTemperature(array $forecast): float
	{
		assert(array_key_exists('instant', $forecast));
		assert(array_key_exists('details', $forecast['instant']));
		assert(array_key_exists('air_temperature', $forecast['instant']['details']));
		return $forecast['instant']['details']['air_temperature'];
	}

	public function getWindSpeed(array $forecast): float
	{
		assert(array_key_exists('instant', $forecast));
		assert(array_key_exists('details', $forecast['instant']));
		assert(array_key_exists('wind_speed', $forecast['instant']['details']));
		return $forecast['instant']['details']['wind_speed'];
	}
}
