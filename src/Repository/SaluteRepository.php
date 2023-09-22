<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use Cvgore\RandomThings\Dto\SaluteEntity;
use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use Random\Randomizer;
use Zend\Stdlib\InitializableInterface;

final class SaluteRepository implements InitializableInterface
{
	#[Inject]
	private readonly Randomizer $randomizer;

	#[Inject]
	private readonly PathGenerator $pathGenerator;

	private array $data;

	public function init(): void
	{
		$this->data = json_decode(
			json: file_get_contents(
				$this->pathGenerator->getResourcePath('salute-pl.json')
			),
			associative: true,
			flags: JSON_THROW_ON_ERROR
		);
	}

	public function getRandomSaluteForCategory(string $category): ?SaluteEntity
	{
		if (! array_key_exists($category, $this->data)) {
			return null;
		}

		$entries = $this->data[$category]['entries'];
		[$item] = $this->randomizer->shuffleArray($entries);

		return new SaluteEntity(content: $item['content'], withGif: $item['withGif']);
	}

	public function getGifTagForCategory(string $category): ?string
	{
		if (! array_key_exists($category, $this->data)) {
			return null;
		}

		return $this->data[$category]['gifTag'];
	}
}
