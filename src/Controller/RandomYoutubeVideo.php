<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\RandomYoutubeVideoResponse;
use Cvgore\RandomThings\Repository\External\YoutubeVideosRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @implements ControllerInterface<void>
 */
final readonly class RandomYoutubeVideo implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private YoutubeVideosRepository $youtubeVideosRepository;

	public function getRoutePattern(): string
	{
		return '/v1/youtube/random';
	}

    public function getRouteMethod(): HttpMethod
    {
        return HttpMethod::Get;
    }

	public function handle(
		Request $request,
		Response $response
	): Response {
		$url = $this->youtubeVideosRepository->getRandomVideoUrl();

		if ($url === null) {
			return $response
				->withStatus(404);
		}

		$body = new RandomYoutubeVideoResponse(videoUrl: $url);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
