<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Console;

use ArrayIterator;
use Cvgore\RandomThings\Collection\WaitIterator;
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
	private readonly MapaInternetowRepository|InMemoryCacheRepository $mapaInternetowRepository;

	#[Inject]
	private readonly YoutubeVideosRepository $youtubeVideosRepository;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$points = $this->mapaInternetowRepository->getPointsIndexedByYoutubeUrl();
		$unavailableVideos = $this->youtubeVideosRepository->getUnavailableVideos(
			null
		);

		$pointsWaitIterator = (new WaitIterator(new ArrayIterator(array_keys(
			$points
		))))
			->setWaitTime(300)
			->setLeeway(1000)
		;

		foreach ($pointsWaitIterator as $url) {
			if (in_array($url, $unavailableVideos, true)) {
				$output->writeln(
					"<comment>skipping {$url} due to previous unavailability</comment>"
				);
				continue;
			}

			$output->writeln("<comment>checking {$url}</comment>");
			$this->youtubeVideosRepository->updateVideoAvailability($url);
		}

		return Command::SUCCESS;
	}
}
