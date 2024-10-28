<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Runtime;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use DI\Container;
use Slim\App;
use Slim\Factory\AppFactory;

final class WebRuntime implements RuntimeInterface
{
	public function start(Container $container): void
	{
		$app = AppFactory::createFromContainer($container);
		$container->set('app', $app);
		$container->set(App::class, $app);
		$this->runConfigurators($container);

		$app->run();
	}

	private function runConfigurators(Container $container): void
	{
        /** @var ConfiguratorInterface[] $configurators */
        $configurators = $container->get('#configurators');

        foreach ($configurators as $configurator) {
            $configurator->configure();
        }

		/** @var ConfiguratorInterface[] $configurators */
		$configurators = $container->get('#web.configurators');

		foreach ($configurators as $configurator) {
			$configurator->configure();
		}
	}
}
