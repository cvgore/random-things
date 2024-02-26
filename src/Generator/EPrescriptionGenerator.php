<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Exception\NotAllocatedStreamException;
use DI\Attribute\Inject;
use Imagick;
use ImagickDraw;
use ImagickPixel;

final readonly class EPrescriptionGenerator
{
    #[Inject]
    private PathGenerator $pathGenerator;

	/**
	 * @return resource
	 */
	public function generateStream(
        string $itemName,
        string $patientName,
        string $issuedBy,
        string $code,
        string $doseText,
    )
	{
		$canvas = $this->makePrescriptionOnTemplate(
            $itemName, $patientName, $issuedBy, $code, $doseText
        );

		$stream = fopen('php://memory', 'w+');

		if (! $stream) {
			throw new NotAllocatedStreamException();
		}

		$canvas->writeImagesFile($stream);

		return $stream;
	}

	private function makePrescriptionOnTemplate(
        string $itemName,
        string $patientName,
        string $issuerName,
        string $code,
        string $doseText,
    ): Imagick
	{
		$paper = new Imagick($this->pathGenerator->getResourcePath('e-prescription.jpg'));

        $blackColor = new ImagickPixel('#000000');

        $fontTitle = new ImagickDraw();
        $fontTitle->setFillColor($blackColor);
        $fontTitle->setFontSize(30);
        $fontTitle->setFont($this->pathGenerator->getResourcePath('fonts/NotoSans.ttf'));

        $fontHead = clone $fontTitle;
        $fontHead->setStrokeColor($blackColor);
        $fontHead->setStrokeWidth(1);
        $fontHead->setFontWeight(700);
        $fontHead->setFontSize(30);

        $fontNote = clone $fontTitle;
        $fontNote->setFontSize(26);

        $fontSubscript = clone $fontTitle;
        $fontSubscript->setFontSize(23);

        $fontNoteBold = clone $fontHead;
        $fontNoteBold->setFontSize(26);

        $paper->setGravity(Imagick::GRAVITY_FORGET);

        $paper->annotateImage($fontTitle, 62, 590, 0, $itemName);
        $paper->annotateImage($fontHead, 240, 378, 0, mb_strtoupper($patientName));
        $paper->annotateImage($fontNote, 240, 425, 0, mb_strtoupper($issuerName));
        $paper->annotateImage($fontHead, 215, 315, 0, $code);
        $paper->annotateImage($fontSubscript, 580, 315, 0, date('j.m.Y'));
        $paper->annotateImage($fontNote, 62, 630, 0, $doseText);
        $paper->annotateImage($fontNote, 62, 700, 0, 'Odpłatność');
        $paper->annotateImage($fontNoteBold, 210, 700, 0, '100%');

		return $paper;
	}
}
