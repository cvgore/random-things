<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use Cvgore\RandomThings\Access\Program;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sentry\State\Scope;

final readonly class SetSentryContext implements MiddlewareInterface
{
	public function process(
		ServerRequestInterface $request,
		RequestHandlerInterface $handler
	): ResponseInterface {
		\Sentry\configureScope(function (Scope $scope) use ($request) {
			$scope->setContext('runtime', [
				'name' => 'web',
				'version' => PHP_VERSION,
			]);

			$scope->setContext('app', [
				'app_name' => 'random-things',
			]);

			/** @var Program $program */
			$program = $request->getAttribute(Program::class);
			$scope->setContext('program', [
				'name' => $program->getIdentifier(),
			]);
		});

		return $handler->handle($request);
	}
}
