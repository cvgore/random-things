<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Runtime;

use DI\Container;

interface RuntimeInterface
{
	public function start(Container $container): void;
}
