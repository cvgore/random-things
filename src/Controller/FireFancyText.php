<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\FancyTextRequest;
use Cvgore\RandomThings\Exception\InvalidRequestInputValueException;
use Cvgore\RandomThings\Exception\NotSupportedValueException;
use Cvgore\RandomThings\Exception\PayloadTooLargeException;
use Cvgore\RandomThings\Generator\FancyFontGenerator;
use DI\Attribute\Inject;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @implements ControllerInterface<FancyTextRequest>
 */
final readonly class FireFancyText implements ControllerInterface
{
	#[Inject(name: '#fancy_font.generator.fire')]
	private FancyFontGenerator $fancyFontGenerator;

	#[Inject(name: 'fancy_font.family.fire.max_len')]
	private int $textMaxLen;

	public function getRoutePattern(): string
	{
		return '/v1/text/fancy/fire';
	}

	public function handle(
		Request $request,
		Response $response,
		FancyTextRequest $data
	): Response {
		if (mb_strlen($data->text) > $this->textMaxLen) {
			throw new PayloadTooLargeException($request);
		}

		try {
			$stream = $this
				->fancyFontGenerator
				->generateStream($data->text);
		} catch (NotSupportedValueException $ex) {
			throw new InvalidRequestInputValueException($request, 'text', previous: $ex);
		}

		return $response
			->withBody(new Stream($stream))
			->withHeader('Content-Type', 'image/gif')
			->withStatus(200);
	}
}
