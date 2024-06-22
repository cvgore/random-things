<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Repository\External\OpenAiRepository;
use DI\Attribute\Inject;

final readonly class MordaTauntsGenerator
{
	#[Inject]
	private OpenAiRepository $openAiRepository;

	#[Inject]
	private PathGenerator $pathGenerator;

	/**
	 * @return string[]
	 */
	public function generate(): array
	{
		$prompt = file_get_contents(
			$this->pathGenerator->getResourcePath('morda.prompt')
		);

		$text = $this->openAiRepository->generateText(
			prompt: $prompt,
			temperature: 1.05,
			maxTokens: 1024,
			topP: 1,
			frequencyPenalty: 0.05,
			presencePenalty: 0,
		);

		return explode("\n", $text ?? '');
	}
}
