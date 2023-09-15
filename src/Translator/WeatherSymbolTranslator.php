<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Translator;

use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use Zend\Stdlib\InitializableInterface;

final class WeatherSymbolTranslator implements InitializableInterface
{
	/**
	 * @var array<string,string>
	 */
	private array $dictionary;

	#[Inject]
	private PathGenerator $pathGenerator;

	public function init(): void
	{
		$this->dictionary = json_decode(
			json: file_get_contents($this->pathGenerator->getResourcePath('weather-symbols-pl.json')),
			associative: true,
			flags: JSON_THROW_ON_ERROR
		);
	}

	public function translate(string $symbol): string
	{
		return $this->dictionary[$symbol];
	}
}
