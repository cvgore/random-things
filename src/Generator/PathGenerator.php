<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use DI\Attribute\Inject;

final readonly class PathGenerator
{
	#[Inject(name: '#path.root')]
	private string $rootDir;

	public function getRootPath(string $path = null): string
	{
		return $path ? "{$this->rootDir}/{$path}" : $this->rootDir;
	}

	public function getResourcePath(string $path = null): string
	{
		return $this->getRootPath($path ? "resources/{$path}" : 'resources');
	}
}
