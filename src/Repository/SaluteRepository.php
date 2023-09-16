<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use Random\Randomizer;
use Symfony\Component\Serializer\SerializerInterface;
use Zend\Stdlib\InitializableInterface;
use Cvgore\RandomThings\Dto\SaluteEntity;

final class SaluteRepository implements InitializableInterface
{
	#[Inject]
	private readonly Randomizer $randomizer;

	#[Inject]
	private readonly PathGenerator $pathGenerator;

	#[Inject]
	private readonly SerializerInterface $serializer;

	private array $data;

	public function init(): void
	{
		$this->data = json_decode(
			json: file_get_contents($this->pathGenerator->getResourcePath('salute.json')),
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
		[$itemId] = $this->randomizer->pickArrayKeys($entries, 1);
		$entry = $entries[$itemId];

		return new SaluteEntity(
			content: $entry['content'],
			withGif: $entry['withGif']
		);
	}

	public function getGifTagForCategory(string $category): ?string
	{
		if (! array_key_exists($category, $this->data)) {
			return null;
		}

		return $this->data[$category]['gifTag'];
	}
}
