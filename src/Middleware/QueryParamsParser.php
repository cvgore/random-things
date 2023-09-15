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

final readonly class QueryParamsParser implements MiddlewareInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	public function process(Request $request, RequestHandler $handler): Response
	{
		if ($request->getMethod() === 'GET' && count($request->getQueryParams()) !== 0) {
			$routeCtx = RouteContext::fromRequest($request);
			$controller = $routeCtx->getRoute()
				->getCallable();

			if ($controller instanceof Closure) {
				return $handler->handle($request);
			}
			$function = new \ReflectionMethod(...explode(':', $controller));

			if ($function->getNumberOfParameters() === 3) {
				$contents = $request->getUri()
					->getQuery();
				if ($contents === '') {
					throw new HttpBadRequestException($request, 'expected query params');
				}

				[,,$arg] = $function->getParameters();
				$type = $arg->getType();

				if ($type instanceof ReflectionNamedType) {
					$deserialized = $this->serializer->deserialize($contents, $type->getName(), 'querystring');

					$request = $request
						->withParsedBody($deserialized)
						->withAttribute('data', $deserialized);
				}
			}
		}

		return $handler->handle($request);
	}
}
