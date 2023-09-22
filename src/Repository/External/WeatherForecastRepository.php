<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\WeatherForecast;
use Cvgore\RandomThings\Extractor\WeatherForecastExtractor;
use Cvgore\RandomThings\Http\HttpClient;
use Cvgore\RandomThings\Translator\Translator;
use Cvgore\RandomThings\Translator\WindSpeedTranslator;
use DI\Attribute\Inject;

final readonly class WeatherForecastRepository
{
	#[Inject(name: 'weather_api.url')]
	private string $baseUrl;

	#[Inject]
	private HttpClient $client;

	#[Inject]
	private WindSpeedTranslator $windSpeedTranslator;

	#[Inject]
	private WeatherForecastExtractor $weatherForecastExtractor;

	#[Inject]
	private Translator $translator;

	public function getForecastForToday(
		string $latitude,
		string $longitude
	): ?WeatherForecast {
		$body = $this->client->get(
			"{$this->baseUrl}/weatherapi/locationforecast/2.0/compact",
			[
				'lat' => $latitude,
				'lon' => $longitude,
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

		$symbolCode = $this->weatherForecastExtractor->getSymbolCode($forecast);
		if ($symbolCode === null) {
			$translatedSymbolCode = $this->translator->translate(
				'weather.missing-symbol-code'
			);
		} else {
			$translatedSymbolCode = $this->translator->translate(
				"weather.symbol-code.{$symbolCode}"
			);
		}

		$windSpeed = $this->weatherForecastExtractor->getWindSpeed($forecast);
		$windSpeedDescription = $this->windSpeedTranslator->translate($windSpeed);

		return new WeatherForecast(
			briefDescription: $translatedSymbolCode,
			temperature: $this->weatherForecastExtractor->getTemperature($forecast),
			windSpeed: $windSpeed,
			windSpeedDescription: $windSpeedDescription,
		);
	}
}
