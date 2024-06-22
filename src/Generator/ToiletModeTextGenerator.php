<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Repository\External\OpenAiRepository;
use DI\Attribute\Inject;

final readonly class ToiletModeTextGenerator
{
	#[Inject]
	private OpenAiRepository $openAiRepository;

	#[Inject]
	private PathGenerator $pathGenerator;

	public function generate(): string
	{
		$prompt = file_get_contents(
			$this->pathGenerator->getResourcePath('toilet-mode.prompt')
		);

		$defaultText = file_get_contents(
			$this->pathGenerator->getResourcePath('toilet-mode.default')
		);

		return $this->openAiRepository->generateText($prompt) ?? $defaultText;
	}
}
