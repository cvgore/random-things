<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Console;

use Cvgore\RandomThings\Repository\CacheRepository;
use Cvgore\RandomThings\Repository\External\Discord\ChannelsRepository;
use Cvgore\RandomThings\Repository\External\FrontOvhRepository;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'check-ovh-servers-availability',
	description: 'checks ovh servers availability'
)]
class CheckOvhServersAvailability extends Command
{
	private const CACHE_KEY = 'ovh-servers-availability';

	#[Inject]
	private readonly FrontOvhRepository $frontOvhRepository;

	#[Inject]
	private readonly CacheRepository $cache;

	#[Inject]
	private readonly ChannelsRepository $channelsRepository;

	#[Inject(name: 'notifications.discord.user_id')]
	private readonly string $discordUserId;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$datacenters = $this->frontOvhRepository->isAvailableServer();
		$availableDc = json_encode($datacenters);

		$jsonLastAvailableInDc = $this->cache->get(self::CACHE_KEY, '[]');

		$lastAvailableDc = json_decode(
			$jsonLastAvailableInDc,
			true,
			flags: JSON_THROW_ON_ERROR
		);

		if (count(array_diff($datacenters, $lastAvailableDc)) !== 0
			|| count(array_diff($lastAvailableDc, $datacenters)) !== 0
		) {
			$output->writeln('Availability change found. Sending notification');

			$channelId = $this->channelsRepository->createPrivateMessageChannel(
				$this->discordUserId
			);
			assert($channelId !== null);
			$this->channelsRepository->sendMessageToChannel(
				$channelId, /* language=md */
				<<<MD
## Ovh KS-A Servers Checker
Change in availability!
Available in **these datacenters**:
```json
{$availableDc}
```
Previously:
```json
{$jsonLastAvailableInDc}
```
<https://eco.ovhcloud.com/pl/kimsufi/ks-a/>
MD
			);
		} else {
			$output->writeln('No availability change found!');
		}

		$this->cache->set(self::CACHE_KEY, $availableDc);

		return Command::SUCCESS;
	}
}
