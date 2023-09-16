<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class SaluteResponse
{
	public function __construct(
		public string $salute,
		public ?string $gifUrl,
	) {
	}
}
