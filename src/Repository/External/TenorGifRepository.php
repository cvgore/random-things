<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class TenorGifRepository implements GifRepositoryInterface
{
	#[Inject(name: 'tenor_gif.url')]
	private string $baseUrl;

	#[Inject(name: 'tenor_gif.api_key')]
	private string $apiKey;

	#[Inject(name: 'tenor_gif.client_key')]
	private string $clientKey;	

	#[Inject(name: 'tenor_gif.default_gif_url')]
	private string $defaultGifUrl;
	
	#[Inject(name: 'tenor_gif.country')]
	private string $country;

	#[Inject(name: 'tenor_gif.locale')]
	private string $locale;

	#[Inject]
	private HttpClient $client;

	public function getRandomGifForQuery(string $query): ?string
	{
		$body = $this->client->get("{$this->baseUrl}/v2/search", [
			'key' => $this->apiKey,
			'q' => $query,
			'client_key' => $this->clientKey,
			'country' => $this->country,
			'locale' => $this->locale,
			'media_filter' => 'gif',
			'random' => true,
			'limit' => 1,
		]);

		if ($body === null) {
			return null;
		}

		assert(array_key_exists('results', $body));
		assert(count($body['results']) === 1);
		assert(array_key_exists('media_formats', $body['results'][0]));
		assert(array_key_exists('gif', $body['results'][0]['media_formats']));
		assert(array_key_exists('url', $body['results'][0]['media_formats']['gif']));

		return $body['results'][0]['media_formats']['gif']['url'];
	}

	public function getDefaultGif(): string
	{
		return $this->defaultGifUrl;
	}
}
