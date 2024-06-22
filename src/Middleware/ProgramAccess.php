<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Cvgore\RandomThings\Access\Program;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

final readonly class ProgramAccess implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler): Response
	{
		/** @var non-empty-list<Program> $programs */
		$programs = $request->getAttribute(Program::class, [Program::Internal]);
		/** @var string $token */
		$token = $request->getAttribute('api-token');

		$programScopedToken = preg_match(
			'#^p:(?<program>[a-z]+):[a-z0-9=]+$#',
			$token,
			$matches
		);
		// it means token is internal (not program scoped)
		if ($programScopedToken === 0) {
			return $handler->handle($request);
		}

		['program' => $tokenProgramIdentifier] = $matches;

		$tokenProgram = Program::fromIdentifier($tokenProgramIdentifier);

		if (! in_array($tokenProgram, $programs, true)) {
			throw new HttpUnauthorizedException(
				$request,
				'This token has no access to given endpoint'
			);
		}

		return $handler->handle($request);
	}
}
