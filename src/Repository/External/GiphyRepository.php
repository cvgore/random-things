<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class GiphyRepository
{
	#[Inject(name: 'giphy.url')]
	private string $baseUrl;

	#[Inject(name: 'giphy.api_key')]
	private string $apiKey;

	#[Inject]
	private HttpClient $client;

	public function getRandomGifForTag(string $tag): ?string
	{
		$body = $this->client->get("{$this->baseUrl}/v1/gifs/random", [
			'api_key' => $this->apiKey,
			'tag' => $tag,
		]);

		if ($body === null) {
			return null;
		}

		assert(array_key_exists('data', $body));
		assert(array_key_exists('embed_url', $body['data']));

		return $body['data']['embed_url'];
	}
}
