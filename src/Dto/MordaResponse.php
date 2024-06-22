<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class MordaResponse
{
	public function __construct(
		public string $batchId,
		/**
		 * @var Taunt[]
		 */
		public array $taunts
	) {
	}
}
