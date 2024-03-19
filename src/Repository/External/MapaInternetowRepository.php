<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\MapaInternetow\Database;
use Cvgore\RandomThings\Dto\MapaInternetow\Link;
use Cvgore\RandomThings\Dto\MapaInternetow\Point;
use Cvgore\RandomThings\Http\HttpClient;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Symfony\Component\Serializer\SerializerInterface;

final class MapaInternetowRepository
{
	#[Inject]
	private HttpClient $client;

	#[Inject(name: 'mapa_internetow.url')]
	private string $baseUrl;

	#[Inject]
	private SerializerInterface $serializer;

	/**
	 * @return array<array-key,Point>
	 */
	public function getPoints(): array
	{
		$rawBody = $this->client->raw(HttpMethod::Get, $this->baseUrl);

		if ($rawBody === null) {
			return [];
		}

		$database = $this->serializer->deserialize(
			(string) $rawBody->getBody(),
			Database::class,
			'json'
		);

		return $database->points;
	}

	/**
	 * @return array<string, Point>
	 */
	public function getPointsIndexedByYoutubeUrl(): array
	{
		$hashmap = [];

		foreach ($this->getPoints() as $point) {
			$ytLinks = array_filter(
				$point->links,
				fn (Link $link) => $link->type === 'yt'
			);

			foreach ($ytLinks as $link) {
				if (! array_key_exists($link->url, $hashmap)) {
					$hashmap[$link->url] = $point;
				}
			}
		}

		return $hashmap;
	}
}
