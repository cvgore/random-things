<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Translator;

use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use Psr\Container\ContainerInterface;
use Zend\Stdlib\InitializableInterface;

final class Translator implements InitializableInterface
{
	/**
	 * @var string[]
	 */
	private array $dictionary;

	#[Inject]
	private PathGenerator $pathGenerator;

	#[Inject]
	private ContainerInterface $container;

	public function init(): void
	{
		$lang = $this->getCurrentLanguage();

		$this->dictionary = json_decode(
			json: file_get_contents(
				$this->pathGenerator->getResourcePath("lang-{$lang}.json")
			),
			associative: true,
			flags: JSON_THROW_ON_ERROR
		);
	}

	/**
	 * @param string|int|float ...$replacements
	 */
	public function translate(string $key, ...$replacements): string
	{
		return sprintf($this->dictionary[$key] ?? $key, ...$replacements);
	}

	public function getCurrentLanguage(): string
	{
		/** @var string */
		return $this->container->get('lang');
	}
}
