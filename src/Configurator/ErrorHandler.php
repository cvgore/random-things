<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;
use Psr\Container\ContainerInterface;
use Slim\App;

final readonly class ErrorHandler implements ConfiguratorInterface
{
	#[Inject]
	private App $app;

	#[Inject]
	private ContainerInterface $container;

	public function configure(): void
	{
		/** @var bool $showErrors */
		$showErrors = $this->container->get('show_errors');

		ini_set('display_errors', $showErrors);
		ini_set('display_startup_errors', $showErrors);
		ini_set('expose_php', 0);

		$this->app
			->addErrorMiddleware($showErrors, true, true);
	}
}
