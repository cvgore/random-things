<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Attribute;

use Attribute;
use Cvgore\RandomThings\Access\Program;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class RouteProgram
{
	public array $programs;

	public function __construct(
		array $programs = [],
		bool $allPrograms = false,
	) {
		if (count($programs) === 0 && $allPrograms === false) {
			throw new InvalidArgumentException(
				'at least one programs or allPrograms should be set'
			);
		}

		if ($allPrograms === true) {
			$this->programs = Program::cases();
		} else {
			$this->programs = $programs;
		}
	}
}
