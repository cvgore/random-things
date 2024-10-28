<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Configurator\Common;

use Cvgore\RandomThings\Configurator\ConfiguratorInterface;
use DI\Attribute\Inject;

final readonly class Sentry implements ConfiguratorInterface
{
	#[Inject(name: 'sentry.dsn')]
	private string $dsn;

	public function configure(): void
	{
        if ($this->dsn === '') {
            return;
        }

		\Sentry\init([
			'dsn' => $this->dsn,
			// Specify a fixed sample rate
			'traces_sample_rate' => 1.0,
			// Set a sampling rate for profiling - this is relative to traces_sample_rate
			'profiles_sample_rate' => 1.0,
		]);
	}
}
