<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\WeatherForecast;
use Cvgore\RandomThings\Http\HttpClient;
use Cvgore\RandomThings\Translator\WeatherSymbolTranslator;
use DI\Attribute\Inject;

final readonly class WeatherForecastRepository
{
	#[Inject(name: 'weather_api.url')]
	private string $baseUrl;

	#[Inject(name: 'weather_api.location')]
	private array $location;

	#[Inject]
	private HttpClient $client;

	#[Inject]
	private WeatherSymbolTranslator $symbolTranslator;

	public function getForecastForToday(): ?WeatherForecast
	{
		$body = $this->client->get(
			"{$this->baseUrl}/weatherapi/locationforecast/2.0/compact",
			[
				'lat' => $this->location['lat'],
				'lon' => $this->location['lng'],
			]
		);
		
		if ($body === null) {
			return null;
		}

		assert(array_key_exists('properties', $body));
		assert(array_key_exists('timeseries', $body['properties']));
		assert(is_array($body['properties']['timeseries']));

		$timeseriesId = min(2, count($body['properties']['timeseries']));
		$timeseries = $body['properties']['timeseries'][$timeseriesId];
		assert(array_key_exists('data', $timeseries));
		$forecast = $timeseries['data'];

		$symbolCode = $this->getSymbolCode($forecast);
		$translatedSymbolCode = $this->symbolTranslator->translate($symbolCode);

		return new WeatherForecast(
			briefDescription: $translatedSymbolCode,
			symbolCode: $symbolCode,
			temperature: $this->getTemperature($forecast),
			windSpeed: $this->getWindSpeed($forecast),
		);
	}

	private function getSymbolCode(array $forecast): ?string
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

	private function getTemperature(array $forecast): float
	{
		assert(array_key_exists('instant', $forecast));
		assert(array_key_exists('details', $forecast['instant']));
		assert(array_key_exists('air_temperature', $forecast['instant']['details']));
		return $forecast['instant']['details']['air_temperature'];
	}

	private function getWindSpeed(array $forecast): float
	{
		assert(array_key_exists('instant', $forecast));
		assert(array_key_exists('details', $forecast['instant']));
		assert(array_key_exists('wind_speed', $forecast['instant']['details']));
		return $forecast['instant']['details']['wind_speed'];
	}
}
