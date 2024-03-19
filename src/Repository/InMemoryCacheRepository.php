<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

final class InMemoryCacheRepository
{
	/**
	 * @var array<array-key,array<array-key, mixed>>
	 */
	private array $cache = [];

	public function __construct(
		private readonly object $inner
	) {
	}

	public function __call(string $name, array $arguments)
	{
		$args = serialize($arguments);
		if (array_key_exists($name, $this->cache) && array_key_exists(
			$args,
			$this->cache[$name]
		)) {
			return $this->cache[$name][$args];
		}

		$this->cache[$name][$args] = call_user_func_array(
			[$this->inner, $name],
			$arguments
		);
		return $this->cache[$name][$args];
	}
}
