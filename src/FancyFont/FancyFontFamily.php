<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\FancyFont;

use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use InvalidArgumentException;

abstract readonly class FancyFontFamily
{
	protected const CHARS_ALPHA = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	protected const CHARS_ALPHA_PL_DIACTRICS = 'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ';

	protected const CHARS_NUM = '1234567890';

	protected const CHARS_SPECIAL = ' ' . '!?,.@:%+';

	private const CHAR_TO_FILENAME_MAP = [
		' ' => '<space>',
		'.' => '<dot>',
		',' => '<comma>',
		'!' => '<exclamation>',
		'?' => '<question>',
		':' => '<colon>',
		'%' => '<percent>',
		'+' => '<plus>',
		'@' => '<at>',
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7' => '7',
		'8' => '8',
		'9' => '9',
		'0' => '0',
	];

	/**
	 * @var string[]
	 */
	private array $charset;

	/**
	 * @psalm-suppress PropertyNotSetInConstructor
	 */
	#[Inject]
	private PathGenerator $pathGenerator;

	public function __construct(
		private string $fontFamily
	) {
		/** @psalm-suppress NamedArgumentNotAllowed */
		$this->charset = array_merge(
			...array_map(mb_str_split(...), $this->getSupportedChars())
		);
	}

	final public function getSymbolPath(string $symbol): string
	{
		if (! $this->isSymbolSupported($symbol)) {
			throw new InvalidArgumentException(
				"char [{$symbol}] not supported by font [{$this->fontFamily}]"
			);
		}

		if (array_key_exists($symbol, self::CHAR_TO_FILENAME_MAP)) {
			$filename = self::CHAR_TO_FILENAME_MAP[$symbol];
		} else {
			$symbolLowerCase = mb_strtolower($symbol);
			$filename = $symbolLowerCase === $symbol
				? "{$symbolLowerCase}l"
				: "{$symbolLowerCase}u";
		}

		return $this->pathGenerator->getResourcePath(
			"fancyfonts/{$this->fontFamily}/{$filename}.gif"
		);
	}

	final public function isSymbolSupported(string $char): bool
	{
		assert(mb_strlen($char) === 1);

		return in_array($char, $this->charset, true);
	}

	/**
	 * @return string[]
	 */
	abstract protected function getSupportedChars(): array;
}
