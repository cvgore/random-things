<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use Cvgore\RandomThings\Repository\External\GifRepositoryInterface;
use DI\Attribute\Inject;
use Random\Randomizer;

final readonly class GifChainRepository implements GifRepositoryInterface
{
    /**
     * @var GifRepositoryInterface[]
     */
    #[Inject(name: 'gif_chain_repositories')]
    private array $repositories;

    #[Inject]
    private Randomizer $randomizer;

    public function getRandomGifForQuery(string $query): ?string
    {
        return $this->getRandomRepository()->getRandomGifForQuery($query);
    }
    
	public function getDefaultGif(): string
    {
        return $this->getRandomRepository()->getDefaultGif();
    }
    
    private function getRandomRepository(): GifRepositoryInterface
    {
        [$repositoryId] = $this->randomizer->pickArrayKeys($this->repositories, 1);
        
        return $this->repositories[$repositoryId];
    }
}
