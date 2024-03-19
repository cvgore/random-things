<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\MapaInternetow\Link;
use Cvgore\RandomThings\Http\HttpClient;
use Cvgore\RandomThings\Repository\InMemoryCacheRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use GuzzleHttp\RequestOptions;
use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

final class YoutubeVideosRepository
{
	#[Inject(name: 'random_yt_movie.tries')]
	private int $tries;

	#[Inject(name: 'random_yt_movie.url')]
	private string $ytUrl;

	#[Inject]
	private HttpClient $client;

	#[Inject(MapaInternetowRepository::class)]
	private MapaInternetowRepository|InMemoryCacheRepository $mapaInternetowRepository;

	public function getRandomVideoUrl(bool $realRandom = false): ?string
	{
		$randomizerEngine = $realRandom
			? new Secure()
			: new Xoshiro256StarStar(
				(int) (new \DateTimeImmutable())->format('Ymd')
			);
		$random = new Randomizer($randomizerEngine);

		$mapsData = $this->mapaInternetowRepository->getPoints();

		for ($_ = 0; $_ < $this->tries; $_++) {
			[$key] = $random->pickArrayKeys($mapsData, 1);

			$links = $mapsData[$key]->links;
			$links = array_filter($links, fn (Link $link) => $link->type === 'yt');

			if (count($links) === 0) {
				unset($mapsData[$key]);

				continue;
			}

			assert($links[0] instanceof Link);

			foreach ($links as $link) {
				if ($this->isVideoOnline($link->url)) {
					return $link->url;
				}
			}

			unset($mapsData[$key]);
		}

		return null;
	}

	public function isVideoOnline(string $url): bool
	{
		$response = $this->client->raw(HttpMethod::Get, $this->ytUrl, [
			RequestOptions::QUERY => [
				'url' => $url,
				'format' => 'json',
			],
		]);

		return $response?->getStatusCode() === 200;
	}
}
