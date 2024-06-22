<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class Taunt
{
	public function __construct(
		public string $id,
		public string $text,
	) {
	}
}
