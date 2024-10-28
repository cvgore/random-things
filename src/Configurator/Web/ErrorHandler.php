<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator\Web;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use Cvgore\RandomThings\Exception\Handler\Web\SentryProxyErrorHandler;
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

		$middleware = $this->app
			->addErrorMiddleware($showErrors, true, true);

		$middleware->setDefaultErrorHandler(new SentryProxyErrorHandler(
			$middleware->getDefaultErrorHandler()
		));
	}
}
