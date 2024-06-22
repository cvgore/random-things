<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class MordaFeedbackRequest
{
	public function __construct(
		public string $tauntId,
	) {
	}
}
