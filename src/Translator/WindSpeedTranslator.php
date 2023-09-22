<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Translator;

use DI\Attribute\Inject;

final class WindSpeedTranslator
{
	#[Inject]
	private Translator $translator;

	public function translate(float $speed): string
	{
		$value = $this->getBeaufortValue($speed);

		return $this->translator->translate("weather.wind.beaufort-scale.{$value}");
	}

	private function getBeaufortValue(float $speedMps): int
	{
		return match (true) {
			$speedMps < 0.5 => 0,
			$speedMps <= 1.5 => 1,
			$speedMps <= 3.3 => 2,
			$speedMps <= 5.4 => 3,
			$speedMps <= 7.9 => 4,
			$speedMps <= 10.7 => 5,
			$speedMps <= 13.8 => 6,
			$speedMps <= 17.1 => 7,
			$speedMps <= 20.7 => 8,
			$speedMps <= 24.4 => 9,
			$speedMps <= 28.4 => 10,
			$speedMps <= 32.6 => 11,
			$speedMps > 32.6 => 12
		};
	}
}
