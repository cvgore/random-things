<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Calculator;

use DateTimeImmutable;

final readonly class NextEasterCalculator
{
	public function calculate(): DateTimeImmutable
	{
		$year = (int) (new DateTimeImmutable())->format('Y');
		$G = $year % 19;
		$C = (int) ($year / 100);
		$H = ($C - (int) ($C / 4) - (int) ((8 * $C + 13) / 25) + 19 * $G + 15) % 30;
		$I = $H - (int) ($H / 28) * (1 - (int) ($H / 28) * (int) (29 / ($H + 1)) * ((21 - $G) / 11));
		$J = ($year + (int) ($year / 4) + $I + 2 - $C + (int) ($C / 4)) % 7;
		$L = $I - $J;
		$m = 3 + (int) (($L + 40) / 44);
		$d = $L + 28 - 31 * ((int) ($m / 4));
		$y = $year;

		return new DateTimeImmutable("{$y}-{$m}-{$d}T00:00:00Z");
	}
}
