<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

final readonly class NotFoundFallbackRouter implements ConfiguratorInterface
{
	#[Inject]
	private App $app;

	public function configure(): void
	{
		$this->app->map(
			['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
			'/{routes:.+}',
			function (Request $request) {
				throw new HttpNotFoundException($request);
			}
		);
	}
}
