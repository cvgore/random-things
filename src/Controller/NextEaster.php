<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\NextEasterResponse;
use DateTimeImmutable;
use DateInterval;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\Serializer\SerializerInterface;

final readonly class NextEaster implements ControllerInterface
{
    #[Inject]
    private SerializerInterface $serializer;

    public function getRoutePattern(): string
    {
        return '/v1/easter';
    }

    public function handle(Request $request, Response $response): Response
    {
        $body = new NextEasterResponse();
        $body->nextEasterAt = $this->getEaster();

        $response->getBody()->write($this->serializer->serialize($body, 'json'));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    private function getEaster(): DateTimeImmutable
    {
        $Year = (int)(new DateTimeImmutable())->format('Y');
        $G = $Year % 19;
        $C = (int)($Year / 100);
        $H = (int)($C - (int)($C / 4) - (int)((8 * $C + 13) / 25) + 19 * $G + 15) % 30;
        $I = (int)$H - (int)($H / 28) * (1 - (int)($H / 28) * (int)(29 / ($H + 1)) * ((int)(21 - $G) / 11));
        $J = ($Year + (int)($Year / 4) + $I + 2 - $C + (int)($C / 4)) % 7;
        $L = $I - $J;
        $m = 3 + (int)(($L + 40) / 44);
        $d = $L + 28 - 31 * ((int)($m / 4));
        $y = $Year;

        return new DateTimeImmutable("{$y}-{$m}-{$d}T00:00:00Z");
    }
}