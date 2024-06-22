<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Access\Program;
use Cvgore\RandomThings\Attribute\RouteProgram;
use Cvgore\RandomThings\Dto\MordaResponse;
use Cvgore\RandomThings\Dto\Taunt;
use Cvgore\RandomThings\Generator\MordaTauntsGenerator;
use Cvgore\RandomThings\Repository\MordaTauntsRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @implements ControllerInterface<void>
 */
final readonly class Morda implements ControllerInterface
{
	#[Inject]
	private SerializerInterface $serializer;

	#[Inject]
	private MordaTauntsGenerator $mordaTauntsGenerator;

	#[Inject]
	private MordaTauntsRepository $mordaTauntsRepository;

	public function getRoutePattern(): string
	{
		return '/v1/morda';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Get;
	}

	#[RouteProgram([Program::Morda])]
	public function handle(Request $request, Response $response): Response
	{
		$taunts = $this->mordaTauntsGenerator->generate();

		if (count($taunts) === 0) {
			throw new HttpInternalServerErrorException($request);
		}

		$taunts = array_map(
			function (string $text): Taunt {
				return new Taunt(id: Uuid::v4()->toRfc4122(), text: $text);
			},
			$taunts
		);
		$batchId = Uuid::v4();

		foreach ($taunts as $taunt) {
			$this->mordaTauntsRepository->addTaunt($taunt, $batchId);
		}

		$body = new MordaResponse(
			batchId: $batchId->toString(),
			taunts: $taunts,
		);

		$response->getBody()
			->write($this->serializer->serialize($body, 'json'));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
