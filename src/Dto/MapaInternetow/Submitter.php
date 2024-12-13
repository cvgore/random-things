<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto\MapaInternetow;

final class Submitter
{
	public function __construct(
		public ?string $type,
		public ?string $user,
	) {
	}
}
