<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Attribute\RouteProgram;
use Cvgore\RandomThings\Routing\HttpMethod;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @implements ControllerInterface<void>
 */
final readonly class CheckAccess implements ControllerInterface
{
	public function getRoutePattern(): string
	{
		return '/v1/access';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Get;
	}

	#[RouteProgram(allPrograms: true)]
	public function handle(Request $request, Response $response): Response
	{
		return $response
			->withStatus(200);
	}
}
