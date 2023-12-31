<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

use DateTimeImmutable;

final readonly class NextEasterResponse
{
	public function __construct(
		public DateTimeImmutable $nextEasterAt,
	) {
	}
}
