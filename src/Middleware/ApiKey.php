<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Cvgore\RandomThings\Middleware\Concern\HeaderAuthTrait;
use DI\Attribute\Inject;
use Psr\Http\Server\MiddlewareInterface;

final readonly class ApiKey implements MiddlewareInterface
{
	use HeaderAuthTrait;

	/**
	 * @var string[] $apiKeys
	 */
	#[Inject(name: 'api_keys')]
	private array $apiKeys;

	private function verifyToken(string $given): bool
	{
		foreach ($this->apiKeys as $apiKey) {
			if (hash_equals($apiKey, $given)) {
				return true;
			}
		}

		return false;
	}
}
