<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class News
{
	public function __construct(
		public string $headline,
		public string $sourceUrl
	) {
	}
}
