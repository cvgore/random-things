<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

interface GifRepositoryInterface
{
    public function getRandomGifForQuery(string $query): ?string;
    
    public function getDefaultGif(): string;
}