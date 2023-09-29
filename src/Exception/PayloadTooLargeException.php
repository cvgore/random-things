<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Slim\Exception\HttpException;
use Throwable;

final class PayloadTooLargeException extends HttpException
{
	public function __construct(RequestInterface $request, ?Throwable $previous = null)
	{
		// enforce
		$request = $request->withHeader('Accept', 'application/json');

		parent::__construct(
			$request,
			'',
			StatusCodeInterface::STATUS_PAYLOAD_TOO_LARGE,
			$previous
		);

		$this->setTitle('payload_too_large');
	}
}
