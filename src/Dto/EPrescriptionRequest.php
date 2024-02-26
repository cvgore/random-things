<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class EPrescriptionRequest
{
	public function __construct(
		public string  $patientName,
        public string  $itemName,
        public ?string $issuerName = null,
        public ?string $doseText = null,
        public ?string $code = null,
	) {
	}
}
