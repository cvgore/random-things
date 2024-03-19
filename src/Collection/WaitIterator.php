<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Collection;

use IteratorIterator;

/**
 * @template TKey
 * @template-covariant TValue
 */
class WaitIterator extends IteratorIterator
{
	/**
	 * @var int<0,max>
	 */
	private int $waitTime = 0;

	/**
	 * @var int<0,max>
	 */
	private int $leeway = 0;

	public function next(): void
	{
		$this->sleepWithLeeway();
		parent::next();
	}

	public function rewind(): void
	{
		$this->sleepWithLeeway();
		parent::rewind();
	}

	public function setWaitTime(int $waitTime): static
	{
		assert($waitTime > 0);

		$this->waitTime = $waitTime;

		return $this;
	}

	public function setLeeway(int $leeway): static
	{
		assert($leeway > 0);
		assert($this->waitTime > $leeway);

		$this->leeway = $leeway;

		return $this;
	}

	private function sleepWithLeeway(): void
	{
		$sleepTime = random_int(0, $this->leeway) + $this->waitTime;

		usleep($sleepTime * 1000);
	}
}
