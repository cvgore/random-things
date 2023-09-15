<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class RandomSaluteRequest
{
	public function __construct(
		public string $category,
	) {
	}
}
