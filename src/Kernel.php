<?php

declare(strict_types=1);

namespace Cvgore\RandomThings;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use DI\Container;
use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

final readonly class Kernel
{
	public const ENV_PROD = 'prod';

	public const ENV_DEV = 'dev';

	private Container $container;

	public function __construct()
	{
		$builder = new ContainerBuilder();
		$builder
			->useAttributes(true)
			->useAutowiring(true)
			->addDefinitions(__DIR__ . '/../config/container.php')
			->addDefinitions(__DIR__ . '/../env.php')
		;

		if ($this->getResolvedEnv() === self::ENV_PROD) {
			$builder
				->enableCompilation(__DIR__ . '/../var/tmp')
			;
		}

		$this->container = $builder->build();
	}

	public function initialize(): void
	{
		$app = AppFactory::createFromContainer($this->container);
		$this->container->set('app', $app);
		$this->container->set(App::class, $app);
		$this->runConfigurators();
	}

	public function run(): void
	{
		$this->container->get(App::class)->run();
	}

	private function runConfigurators(): void
	{
		/** @var ConfiguratorInterface[] $configurators */
		$configurators = $this->container->get('configurators');

		foreach ($configurators as $configurator) {
			$configurator->configure();
		}
	}

	private function getResolvedEnv(): string
	{
		$builder = new ContainerBuilder();
		$builder
			->addDefinitions(__DIR__ . '/../env.php')
		;
		$intermediateContainer = $builder->build();

		/** @var string $env */
		$env = $intermediateContainer->get('env');
		return $env;
	}
}
