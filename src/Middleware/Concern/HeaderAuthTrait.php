<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware\Concern;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

trait HeaderAuthTrait
{
	private const AUTH_HEADER_PREFIX = 'Bearer ';

	private const REQUEST_ATTRIBUTE_API_TOKEN = 'api-token';

	private const MAX_AUTH_HEADER_LENGTH = 256;

	public function process(Request $request, RequestHandler $handler): Response
	{
		$header = $request->getHeader('Authorization');

		if (count($header) === 0) {
			throw new HttpUnauthorizedException($request);
		}

		// preserve only first header
		$header = $header[0];
		if (! str_starts_with($header, self::AUTH_HEADER_PREFIX)) {
			throw new HttpUnauthorizedException($request);
		}
		if (mb_strlen($header) > self::MAX_AUTH_HEADER_LENGTH) {
			throw new HttpUnauthorizedException($request);
		}
		$token = mb_substr($header, mb_strlen(self::AUTH_HEADER_PREFIX));

		if (! $this->verifyToken($token)) {
			throw new HttpUnauthorizedException($request);
		}

		$request = $request->withAttribute(self::REQUEST_ATTRIBUTE_API_TOKEN, $token);

		$request = $this->onTokenValid($request);

		return $handler->handle($request);
	}

	private function onTokenValid(Request $request): Request
	{
		return $request;
	}
}
