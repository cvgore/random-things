<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

use Cvgore\RandomThings\Formatter\NewsFormatter;
use Cvgore\RandomThings\Repository\External\NewsRepository;
use DI\Attribute\Inject;

final readonly class NewsProcessor implements MorningSaluteProcessor
{
    #[Inject]
    private NewsRepository $newsRepository;

    #[Inject]
    private NewsFormatter $newsFormatter;

    public function getPlaceholder(): string
    {
        return '@news';
    }

    public function generate(): string
    {
        $news = $this->newsRepository->getRandomTopNews();
        return $this->newsFormatter->format($news);
    }
}