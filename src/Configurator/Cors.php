<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;
use Slim\App;

final readonly class Cors implements ConfiguratorInterface
{
	#[Inject]
	private App $app;

	public function configure(): void
	{
		$this->app->add(\Cvgore\RandomThings\Middleware\Cors::class);
	}
}
