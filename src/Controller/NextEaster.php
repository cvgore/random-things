<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Calculator\NextEasterCalculator;
use Cvgore\RandomThings\Dto\NextEasterResponse;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\Serializer\SerializerInterface;

final readonly class NextEaster implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private NextEasterCalculator $nextEasterCalculator;

	public function getRoutePattern(): string
	{
		return '/v1/easter';
	}

	public function handle(Request $request, Response $response): Response
	{
		$body = new NextEasterResponse(nextEasterAt: $this->nextEasterCalculator->calculate());

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
