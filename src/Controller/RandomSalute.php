<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\RandomSaluteRequest;
use Cvgore\RandomThings\Dto\SaluteResponse;
use Cvgore\RandomThings\Repository\External\GiphyRepository;
use Cvgore\RandomThings\Repository\SaluteRepository;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class RandomSalute implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private GiphyRepository $giphyRepository;

	#[Inject]
	private SaluteRepository $saluteRepository;

	public function getRoutePattern(): string
	{
		return '/v1/salute/random';
	}

	public function handle(Request $request, Response $response, RandomSaluteRequest $data): Response
	{
		$salute = $this->saluteRepository->getRandomSaluteForCategory($data->category);

		if ($salute === null) {
			return $response
				->withStatus(404);
		}

		$gifTag = $this->saluteRepository->getGifTagForCategory($data->category);

		if ($data->category === 'test') {
			$gifUrl = $this->giphyRepository->getDefaultGif();
		} else {
			$gifUrl = $this->giphyRepository->getRandomGifForTag($gifTag);
		}
		$body = new SaluteResponse(salute: $salute, gifUrl: $gifUrl);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
