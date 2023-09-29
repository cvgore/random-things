<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Exception;

use RuntimeException;
use Throwable;

final class NotAllocatedStreamException extends RuntimeException
{
	public function __construct(Throwable $previous = null)
	{
		parent::__construct('failed to allocate stream', 0, $previous);
	}
}
