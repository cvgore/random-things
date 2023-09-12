<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @method Response handle(Request $request, Response $response, mixed $data)
 */
interface ControllerInterface
{
    public function getRoutePattern(): string;
}