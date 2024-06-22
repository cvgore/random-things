<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Closure;
use Cvgore\RandomThings\Access\Program;
use Cvgore\RandomThings\Attribute\RouteProgram;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ReflectionMethod;
use Slim\Routing\RouteContext;

final readonly class ControllerMetadata implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler): Response
	{
		$routeCtx = RouteContext::fromRequest($request);
		assert($routeCtx->getRoute() !== null);
		$controller = $routeCtx->getRoute()
			->getCallable();

		if ($controller instanceof Closure) {
			return $handler->handle($request);
		}

		assert(is_string($controller));
		$function = new ReflectionMethod(...explode(':', $controller));
		$attributes = $function->getAttributes(RouteProgram::class);

		if (count($attributes) !== 0) {
			$attribute = $attributes[0];
			/** @var RouteProgram $routeProgram */
			$routeProgram = $attribute->newInstance();

			$request = $request->withAttribute(Program::class, $routeProgram->programs);
		}

		return $handler->handle($request);
	}
}
