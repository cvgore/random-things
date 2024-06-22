<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Closure;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ReflectionNamedType;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class JsonBodyParser implements MiddlewareInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	public function process(Request $request, RequestHandler $handler): Response
	{
		$contentType = $request->getHeaderLine('Content-Type');

		if ($request->getMethod() === 'POST' && str_contains($contentType, 'application/json')) {
			$routeCtx = RouteContext::fromRequest($request);
			assert($routeCtx->getRoute() !== null);
			$controller = $routeCtx->getRoute()
				->getCallable();

			if ($controller instanceof Closure) {
				return $handler->handle($request);
			}
			assert(is_string($controller));
			$function = new \ReflectionMethod(...explode(':', $controller));

			if ($function->getNumberOfParameters() === 3) {
				$contents = trim(file_get_contents('php://input'));
				if ($contents === '') {
					throw new HttpBadRequestException($request, 'expected body');
				}

				[,,$arg] = $function->getParameters();
				$type = $arg->getType();

				if ($type instanceof ReflectionNamedType) {
					$deserialized = $this->serializer->deserialize(
						$contents,
						$type->getName(),
						'json'
					);

					$request = $request
						->withParsedBody($deserialized)
						->withAttribute('data', $deserialized);
				}
			}
		}

		return $handler->handle($request);
	}
}
