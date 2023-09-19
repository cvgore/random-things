<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class GiphyRepository implements GifRepositoryInterface
{
	#[Inject(name: 'giphy.url')]
	private string $baseUrl;

	#[Inject(name: 'giphy.api_key')]
	private string $apiKey;

	#[Inject(name: 'giphy.default_gif_url')]
	private string $defaultGifUrl;

	#[Inject]
	private HttpClient $client;

	public function getRandomGifForQuery(string $query): ?string
	{
		$body = $this->client->get("{$this->baseUrl}/v1/gifs/random", [
			'api_key' => $this->apiKey,
			'tag' => $query,
		]);

		if ($body === null) {
			return null;
		}

		assert(array_key_exists('data', $body));
		assert(array_key_exists('embed_url', $body['data']));

		return $body['data']['embed_url'];
	}

	public function getDefaultGif(): string
	{
		return $this->defaultGifUrl;
	}
}
