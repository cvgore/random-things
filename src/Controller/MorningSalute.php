<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\SaluteResponse;
use Cvgore\RandomThings\Generator\MorningSaluteGenerator;
use Cvgore\RandomThings\Repository\External\GiphyRepository;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\Serializer\SerializerInterface;

final readonly class MorningSalute implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private GiphyRepository $giphyRepository;

	#[Inject]
	private MorningSaluteGenerator $morningSaluteGenerator;

	public function getRoutePattern(): string
	{
		return '/v1/salute/morning';
	}

	public function handle(Request $request, Response $response): Response
	{
		$salute = $this->morningSaluteGenerator->generate();
		$gifUrl = $this->giphyRepository->getRandomGifForTag('funny cat');

		$body = new SaluteResponse(salute: $salute, gifUrl: $gifUrl);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
