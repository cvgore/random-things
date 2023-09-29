<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\FancyFont;

final readonly class FireFancyFontFamily extends FancyFontFamily
{
	public function __construct()
	{
		parent::__construct('fire');
	}

	protected function getSupportedChars(): array
	{
		return [
			self::CHARS_ALPHA,
			self::CHARS_ALPHA_PL_DIACTRICS,
			self::CHARS_NUM,
			self::CHARS_SPECIAL,
		];
	}
}
