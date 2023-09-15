<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

final readonly class NotFoundFallbackRouter implements ConfiguratorInterface
{
	#[Inject]
	private App $app;

	public function configure(): void
	{
		$this->app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request) {
			throw new HttpNotFoundException($request);
		});
	}
}
