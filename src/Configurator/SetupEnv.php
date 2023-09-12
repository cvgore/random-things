<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator;

use DI\Attribute\Inject;

use DI\Container;

final readonly class SetupEnv implements ConfiguratorInterface
{
    #[Inject]
    private Container $container;
    
    public function configure(): void
    {
        $setupEnvFunction = require __DIR__ . '/../../environment.php';
        $setupEnvFunction($this->container);
    }
}