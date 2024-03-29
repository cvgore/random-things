<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\SaluteResponse;
use Cvgore\RandomThings\Generator\MorningSaluteGenerator;
use Cvgore\RandomThings\Repository\External\YoutubeVideosRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @implements ControllerInterface<void>
 */
final readonly class MorningSalute implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private MorningSaluteGenerator $morningSaluteGenerator;

	#[Inject]
	private YoutubeVideosRepository $youtubeVideosRepository;

	public function getRoutePattern(): string
	{
		return '/v1/salute/morning';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Get;
	}

	public function handle(Request $request, Response $response): Response
	{
		$salute = $this->morningSaluteGenerator->generate();
		$gifUrl = $this->youtubeVideosRepository->getRandomVideoUrl();

		$body = new SaluteResponse(salute: $salute, gifUrl: $gifUrl);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
