<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\UnavailableVideo;
use Cvgore\RandomThings\Dto\UnavailableVideosResponse;
use Cvgore\RandomThings\Repository\External\MapaInternetowRepository;
use Cvgore\RandomThings\Repository\InMemoryCacheRepository;
use Cvgore\RandomThings\Repository\YoutubeVideosRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DateTimeImmutable;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @implements ControllerInterface<void>
 */
final readonly class MapaInternetowUnavailableVideos implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private YoutubeVideosRepository $youtubeVideosRepository;

	#[Inject(MapaInternetowRepository::class)]
	private MapaInternetowRepository|InMemoryCacheRepository $mapaInternetowRepository;

	public function getRoutePattern(): string
	{
		return '/v1/mapainternetow/unavailable';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Get;
	}

	public function handle(Request $request, Response $response): Response
	{
		$urls = $this->youtubeVideosRepository->getUnavailableVideos(
			new DateTimeImmutable('-7 days')
		);

		$unavailable = [];
		foreach ($urls as $url) {
			$point = $this->mapaInternetowRepository->getPointsIndexedByYoutubeUrl()[$url];
			$unavailable[] = new UnavailableVideo(
				pointId: $point->id,
				pointTitle: $point->title,
				videoUrl: $url
			);
		}

		$body = new UnavailableVideosResponse(unavailable: $unavailable);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
