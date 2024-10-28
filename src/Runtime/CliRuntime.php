<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Runtime;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use DI\Container;
use Symfony\Component\Console\Application as CliApp;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

final class CliRuntime implements RuntimeInterface
{
	public function start(Container $container): void
	{
		$app = new CliApp('Random Things', 'current');
		$container->set('app', $app);
		$app->addCommands($container->get('#cli.commands'));
        $app->setCatchExceptions(false);
        $app->setCatchErrors(false);

        $this->runConfigurators($container);

        $output = new ConsoleOutput();
        $input = new ArgvInput();

        try {
            $app->run($input, $output);
        } catch (Throwable $ex) {
            \Sentry\captureException($ex);
            $app->renderThrowable($ex, $output);
        }
	}

    private function runConfigurators(Container $container): void
    {
        /** @var ConfiguratorInterface[] $configurators */
        $configurators = $container->get('#configurators');

        foreach ($configurators as $configurator) {
            $configurator->configure();
        }

        /** @var ConfiguratorInterface[] $configurators */
        $configurators = $container->get('#cli.configurators');

        foreach ($configurators as $configurator) {
            $configurator->configure();
        }
    }
}
