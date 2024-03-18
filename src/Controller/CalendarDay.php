<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\CalendarDayResponse;
use Cvgore\RandomThings\Repository\External\CalendarRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @implements ControllerInterface<void>
 */
final readonly class CalendarDay implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private CalendarRepository $calendarRepository;

	public function getRoutePattern(): string
	{
		return '/v1/calendar/day';
	}

    public function getRouteMethod(): HttpMethod
    {
        return HttpMethod::Get;
    }

    public function handle(
		Request $request,
		Response $response
	): Response {
		$name = $this->calendarRepository->getRandomCalendarDay();

		if ($name === null) {
			return $response
				->withStatus(404);
		}

		$body = new CalendarDayResponse(name: $name);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
