<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class UnavailableVideo
{
	public function __construct(
		public int    $pointId,
		public string $pointTitle,
		public string $videoUrl,
	) {
	}
}
