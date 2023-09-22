<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class WeatherForecast
{
	public function __construct(
		public string $briefDescription,
		public float $temperature,
		public float $windSpeed,
		public string $windSpeedDescription,
	) {
	}
}
