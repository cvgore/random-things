<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Provider;

use DateTimeImmutable;

final readonly class CurrentDateProvider
{
	private const NAME_OF_MONTHS_GENITIVE = [
		'stycznia',
		'lutego',
		'marca',
		'kwietnia',
		'maja',
		'czerwca',
		'lipca',
		'siernia',
		'września',
		'października',
		'listopada',
		'grudnia',
	];

	public function todayLong(): string
	{
		$today = new DateTimeImmutable('today');
		$day = $today->format('d');
		$month = (int) $today->format('n');
		$year = $today->format('Y');

		return sprintf('%s %s %s', $day, self::NAME_OF_MONTHS_GENITIVE[$month], $year);
	}
}
