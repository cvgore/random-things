<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Calculator\NextEasterCalculator;
use Cvgore\RandomThings\Dto\NextEasterResponse;
use Cvgore\RandomThings\Dto\ToiletModeResponse;
use Cvgore\RandomThings\Generator\ToiletModeTextGenerator;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * @implements ControllerInterface<void>
 */
final readonly class ToiletMode implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private ToiletModeTextGenerator $toiletModeTextGenerator;

	public function getRoutePattern(): string
	{
		return '/v1/toilet';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Get;
	}

	public function handle(Request $request, Response $response): Response
	{
		$body = new ToiletModeResponse(
			text: $this->toiletModeTextGenerator->generate()
		);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
