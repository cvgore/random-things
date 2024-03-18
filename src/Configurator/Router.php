<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use Cvgore\RandomThings\Controller\ControllerInterface;
use Cvgore\RandomThings\Routing\ObjectRequestArgs;
use DI\Attribute\Inject;
use Slim\App;

final readonly class Router implements ConfiguratorInterface
{
	#[Inject]
	private App $app;

	#[Inject(name: '#controllers')]
	private array $controllers;

	public function configure(): void
	{
		$this->app->addRoutingMiddleware();

		$routeCollector = $this->app->getRouteCollector();
		$routeCollector->setDefaultInvocationStrategy(new ObjectRequestArgs());

		/** @var ControllerInterface $controller */
		foreach ($this->controllers as $controller) {
			$this->app->map(
                [$controller->getRouteMethod()->value],
				$controller->getRoutePattern(),
				$controller::class . ':handle'
			);
		}
	}
}
