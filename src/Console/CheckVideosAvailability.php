<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Console;

use ArrayIterator;
use Cvgore\RandomThings\Collection\WaitIterator;
use Cvgore\RandomThings\Dto\MapaInternetow\Link;
use Cvgore\RandomThings\Repository\External\MapaInternetowRepository;
use Cvgore\RandomThings\Repository\InMemoryCacheRepository;
use Cvgore\RandomThings\Repository\YoutubeVideosRepository;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'check-videos-availability',
	description: 'checks mapainternetow videos availability'
)]
class CheckVideosAvailability extends Command
{
	#[Inject(MapaInternetowRepository::class)]
	private MapaInternetowRepository|InMemoryCacheRepository $mapaInternetowRepository;

	#[Inject]
	private YoutubeVideosRepository $youtubeVideosRepository;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$points = $this->mapaInternetowRepository->getPoints();
		$pointsWaitIterator = (new WaitIterator(new ArrayIterator($points)))
			->setWaitTime(300)
			->setLeeway(100)
		;

		foreach ($pointsWaitIterator as $point) {
			$links = array_filter(
				$point->links,
				fn (Link $link) => $link->type === 'yt'
			);

			if (count($links) === 0) {
				continue;
			}
			assert($links[0] instanceof Link);

			foreach ($links as $link) {
				$output->writeln("<comment>checking {$link->url}</comment>");
				$this->youtubeVideosRepository->updateVideoAvailability($link->url);
			}
		}

		return Command::SUCCESS;
	}
}
