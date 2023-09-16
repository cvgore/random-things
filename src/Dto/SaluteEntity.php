<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto;

final readonly class SaluteEntity
{
    public function __construct(
        public string $content,
		public bool $withGif,
    ) {
    }
}
