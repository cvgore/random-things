<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto\MapaInternetow;

final class Database
{
	public function __construct(
		public array $maps,
		/**
		 * @var Point[]
		 */
		public array $points,
	) {
	}
}
