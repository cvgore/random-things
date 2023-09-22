<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @template T
 *
 * @method Response handle(Request $request, Response $response, T $data)
 */
interface ControllerInterface
{
	public function getRoutePattern(): string;
}
