<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;
use Slim\App;

final readonly class GlobalMiddleware implements ConfiguratorInterface
{
	#[Inject(name: '#global_middleware')]
	private array $middleware;

	#[Inject]
	private App $app;

	public function configure(): void
	{
		foreach ($this->middleware as $middleware) {
			$this->app->addMiddleware($middleware);
		}
	}
}
