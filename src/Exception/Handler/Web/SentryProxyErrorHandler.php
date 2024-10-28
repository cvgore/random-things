<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Exception\Handler\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

final class SentryProxyErrorHandler implements ErrorHandlerInterface
{
	public function __construct(
		private readonly ErrorHandlerInterface $errorHandler
	) {
	}

	public function __invoke(
		ServerRequestInterface $request,
		Throwable $exception,
		bool $displayErrorDetails,
		bool $logErrors,
		bool $logErrorDetails
	): ResponseInterface {
		\Sentry\captureException($exception);

		return $this->errorHandler->__invoke(...func_get_args());
	}
}
