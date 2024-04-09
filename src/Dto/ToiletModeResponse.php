<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class ToiletModeResponse
{
	public function __construct(
		public string $text,
	) {
	}
}
