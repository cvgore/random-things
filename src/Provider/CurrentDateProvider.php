<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Provider;

use Cvgore\RandomThings\Translator\Translator;
use DateTimeImmutable;
use DI\Attribute\Inject;

final readonly class CurrentDateProvider
{
	#[Inject]
	private Translator $translator;

	public function todayLong(): string
	{
		$today = new DateTimeImmutable('today');
		$day = $today->format('d');
		$month = (int) $today->format('n');
		$year = $today->format('Y');

		return sprintf(
			'%s %s %s',
			$day,
			$this->translator->translate("month.genitive.{$month}"),
			$year
		);
	}
}
