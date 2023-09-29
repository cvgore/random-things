<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

final readonly class ApiKey implements MiddlewareInterface
{
	private const AUTH_HEADER_PREFIX = 'Bearer ';

	/**
	 * @var string[] $apiKeys
	 */
	#[Inject(name: 'api_keys')]
	private array $apiKeys;

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
		$token = mb_substr($header, mb_strlen(self::AUTH_HEADER_PREFIX));

		if (! $this->verifyApiKey($token)) {
			throw new HttpUnauthorizedException($request);
		}

		return $handler->handle($request);
	}

	private function verifyApiKey(string $given): bool
	{
		foreach ($this->apiKeys as $apiKey) {
			if (hash_equals($apiKey, $given)) {
				return true;
			}
		}

		return false;
	}
}
