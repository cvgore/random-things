<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Exception;

use RuntimeException;
use Throwable;

final class NotSupportedValueException extends RuntimeException
{
	public function __construct(string $text, int $code = 0, Throwable $previous = null)
	{
		parent::__construct("value [{$text}] is not supported", $code, $previous);
	}
}
