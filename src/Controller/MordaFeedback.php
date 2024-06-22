<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Access\Program;
use Cvgore\RandomThings\Attribute\RouteProgram;
use Cvgore\RandomThings\Dto\MordaFeedbackRequest;
use Cvgore\RandomThings\Repository\MordaTauntsRepository;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @implements ControllerInterface<MordaFeedbackRequest>
 */
final readonly class MordaFeedback implements ControllerInterface
{
	#[Inject]
	private MordaTauntsRepository $mordaTauntsRepository;

	public function getRoutePattern(): string
	{
		return '/v1/morda/feedback';
	}

	public function getRouteMethod(): HttpMethod
	{
		return HttpMethod::Post;
	}

	#[RouteProgram([Program::Morda])]
	public function handle(
		Request $request,
		Response $response,
		MordaFeedbackRequest $data
	): Response {
		$this->mordaTauntsRepository->setTauntScore($data->tauntId, 100);

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}
}
