<?php

declare(strict_types=1);

namespace Cvgore\RandomThings;

use Cvgore\RandomThings\Runtime\RuntimeInterface;
use DI\Container;
use DI\ContainerBuilder;

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

	public function run(RuntimeInterface $runtime): void
	{
		$runtime->start($this->container);
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
