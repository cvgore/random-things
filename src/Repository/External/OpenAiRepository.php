<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class OpenAiRepository
{
	#[Inject(name: 'openai.url')]
	private string $baseUrl;

	#[Inject(name: 'openai.api_key')]
	private string $apiKey;

	#[Inject]
	private HttpClient $client;

	public function generateText(
		string $prompt,
		?string $model = null,
		?float $temperature = null,
		?int $maxTokens = null,
		?float $topP = null,
		?float $frequencyPenalty = null,
		?float $presencePenalty = null,
	): ?string {
		$body = $this->client
			->withHeader('Authorization', "Bearer {$this->apiKey}")
			->post(
				"{$this->baseUrl}/v1/chat/completions",
				[
					'model' => $model ?? 'gpt-3.5-turbo',
					'messages' => [
						[
							'role' => 'system',
							'content' => $prompt,
						],
					],
					'temperature' => $temperature ?? 1.15,
					'max_tokens' => $maxTokens ?? 2048,
					'top_p' => $topP ?? 0.62,
					'frequency_penalty' => $frequencyPenalty ?? 0.18,
					'presence_penalty' => $presencePenalty ?? 0.25,
				]
			);

		if ($body === null) {
			return null;
		}

		assert(array_key_exists('choices', $body));

		$choices = $body['choices'];
		if (count($choices) === 0) {
			return null;
		}

		assert(array_is_list($choices));
		assert(array_key_exists('message', $choices[0]));
		assert(array_key_exists('content', $choices[0]['message']));

		$message = $choices[0]['message']['content'];

		return trim($message);
	}
}
