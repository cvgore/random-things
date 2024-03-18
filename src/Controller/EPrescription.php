<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\EPrescriptionRequest;
use Cvgore\RandomThings\Exception\InvalidRequestInputValueException;
use Cvgore\RandomThings\Exception\NotSupportedValueException;
use Cvgore\RandomThings\Exception\PayloadTooLargeException;
use Cvgore\RandomThings\Generator\EPrescriptionGenerator;
use Cvgore\RandomThings\Routing\HttpMethod;
use DI\Attribute\Inject;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Random\Randomizer;

/**
 * @implements ControllerInterface<EPrescriptionRequest>
 */
final readonly class EPrescription implements ControllerInterface
{
	#[Inject(name: 'eprescription.patient_name.max_len')]
	private int $patientMaxLen;

    #[Inject(name: 'eprescription.item_name.max_len')]
    private int $itemMaxLen;

    #[Inject(name: 'eprescription.issued_by.max_len')]
    private int $issuedByMaxLen;

    #[Inject(name: 'eprescription.dose_text.max_len')]
    private int $doseTextMaxLen;

    #[Inject(name: 'eprescription.code.length')]
    private int $codeLength;

    #[Inject(name: 'eprescription.issued_by.default_value')]
    private string $issuedByDefaultValue;

    #[Inject(name: 'eprescription.dose_text.default_value')]
    private string $doseTextDefaultValue;

    #[Inject]
    private Randomizer $random;

    #[Inject]
    private EPrescriptionGenerator $eprescriptionGenerator;

	public function getRoutePattern(): string
	{
		return '/v1/eprescription';
	}

    public function getRouteMethod(): HttpMethod
    {
        return HttpMethod::Get;
    }

	public function handle(
		Request $request,
		Response $response,
        EPrescriptionRequest $data
	): Response {
		if (mb_strlen($data->itemName) > $this->itemMaxLen) {
			throw new PayloadTooLargeException($request);
		}
        if ($data->issuerName !== null && mb_strlen($data->issuerName) > $this->issuedByMaxLen) {
            throw new PayloadTooLargeException($request);
        }
        if ($data->doseText !== null && mb_strlen($data->doseText) > $this->doseTextMaxLen) {
            throw new PayloadTooLargeException($request);
        }
        if ($data->code !== null && mb_strlen($data->code) !== $this->codeLength) {
            throw new PayloadTooLargeException($request);
        }
        if (mb_strlen($data->patientName) > $this->patientMaxLen) {
            throw new PayloadTooLargeException($request);
        }

		try {
			$stream = $this
				->eprescriptionGenerator
				->generateStream(
                    $data->itemName,
                    $data->patientName,
                    $data->issuerName ?? $this->issuedByDefaultValue,
                    $data->code ?? sprintf('%d', $this->random->getInt(1000, 9999)),
                    $data->doseText ?? $this->doseTextDefaultValue,
                );
		} catch (NotSupportedValueException $ex) {
			throw new InvalidRequestInputValueException($request, 'text', previous: $ex);
		}

		return $response
			->withBody(new Stream($stream))
			->withHeader('Content-Type', 'image/jpeg')
			->withStatus(200);
	}
}
