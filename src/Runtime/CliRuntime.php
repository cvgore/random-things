<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Runtime;

use DI\Container;
use Symfony\Component\Console\Application as CliApp;

final class CliRuntime implements RuntimeInterface
{
	public function start(Container $container): void
	{
		$app = new CliApp('Random Things', 'current');
		$container->set('app', $app);
		$app->addCommands($container->get('#cli.commands'));
		$app->setCatchExceptions(true);

		$app->run();
	}
}
