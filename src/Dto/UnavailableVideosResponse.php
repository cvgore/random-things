<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class UnavailableVideosResponse
{
	public function __construct(
		/**
		 * @var UnavailableVideo[]
		 */
		public array $unavailable
	) {
	}
}
