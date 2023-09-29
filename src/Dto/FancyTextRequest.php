<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class FancyTextRequest
{
	public function __construct(
		public string $text,
	) {
	}
}
