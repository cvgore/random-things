<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Slim\Exception\HttpException;
use Throwable;

final class InvalidRequestInputValueException extends HttpException
{
	public function __construct(RequestInterface $request, string $key, ?Throwable $previous = null)
	{
		// enforce
		$request = $request->withHeader('Accept', 'application/json');

		parent::__construct(
			$request,
			'',
			StatusCodeInterface::STATUS_BAD_REQUEST,
			$previous
		);

		$this->setTitle("invalid_input_value:{$key}");
	}
}
