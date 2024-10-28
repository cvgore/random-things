<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator\Cli;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use Sentry\State\Scope;

final readonly class SetSentryContext implements ConfiguratorInterface
{
	public function configure(): void
	{
		\Sentry\configureScope(function (Scope $scope) {
            $scope->setContext('runtime', [
                'name' => 'cli',
                'version' => phpversion()
            ]);

            $scope->setContext('app', [
                'app_name' => 'random-things',
            ]);
        });
	}
}
