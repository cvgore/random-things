<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto\MapaInternetow;

use JetBrains\PhpStorm\ExpectedValues;

final class Link
{
	public function __construct(
		#[ExpectedValues(['yt'])]
		public string $type,
		public string $url,
	) {
	}
}
