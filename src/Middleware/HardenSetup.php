<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final readonly class HardenSetup implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler): Response
	{
		return $handler->handle($request);
	}
}
