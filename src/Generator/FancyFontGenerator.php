<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Generator;

use Cvgore\RandomThings\Exception\NotAllocatedStreamException;
use Cvgore\RandomThings\Exception\NotSupportedValueException;
use Cvgore\RandomThings\FancyFont\FancyFontFamily;
use Imagick;

final readonly class FancyFontGenerator
{
	public function __construct(
		private FancyFontFamily $fancyFontFamily,
	) {
	}

	/**
	 * @return resource
	 */
	public function generateStream(string $text)
	{
		if (! $this->isValidText($text)) {
			throw new NotSupportedValueException($text);
		}

		$canvas = $this->makeTextOnCanvas($text);

		// TODO: limit memory usage to 5242880 / 5MiB
		$stream = fopen('php://memory', 'w+');

		if (! $stream) {
			throw new NotAllocatedStreamException();
		}

		$canvas->writeImagesFile($stream);

		return $stream;
	}

	public function isValidText(string $text): bool
	{
		foreach (mb_str_split($text) as $char) {
			if (! $this->fancyFontFamily->isSymbolSupported($char)) {
				return false;
			}
		}

		return true;
	}

	private function makeSymbol(string $symbol): Imagick
	{
		assert(mb_strlen($symbol) === 1);

		return new Imagick($this->fancyFontFamily->getSymbolPath($symbol));
	}

	private function makeTextOnCanvas(string $text): Imagick
	{
		$image = new Imagick();
		$image->setGravity(Imagick::GRAVITY_CENTER);
		$image->setBackgroundColor('none');

		$letters = [];
		foreach (mb_str_split($text) as $letter) {
			$letters[] = $this->makeSymbol($letter)->coalesceImages();
		}

		$framesCount = 5;
		for ($i = 0; $i < $framesCount; $i++) {
			$canvas = new Imagick();
			foreach ($letters as $letter) {
				$letter->setIteratorIndex($i);
				$frame = clone $letter->getImage();
				$frame->setImageBackgroundColor('none');
				$canvas->addImage($frame);
			}
			$canvas->setFirstIterator();
			$image->addImage($canvas->appendImages(false));
		}

		$image->setFirstIterator();
		$image->setImagePage(0, 0, 0, 0);
		$image->setImageDispose(Imagick::DISPOSE_BACKGROUND);
		return $image;
	}
}
