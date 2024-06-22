<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Access;

use InvalidArgumentException;

enum Program
{
	// by default all endpoints are in program Internal
	case Internal;
	case Morda;

	public function getIdentifier(): string
	{
		return mb_convert_case($this->name, MB_CASE_LOWER);
	}

	public static function fromIdentifier(string $given): self
	{
		foreach (self::cases() as $case) {
			$identifier = mb_convert_case($case->name, MB_CASE_LOWER);

			if ($given === $identifier) {
				return $case;
			}
		}

		throw new InvalidArgumentException('invalid program identifier: ' . $given);
	}
}
