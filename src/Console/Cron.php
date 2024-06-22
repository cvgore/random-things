<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Console;

use Cron\CronExpression;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cron', description: 'runs schedule')]
final class Cron extends Command
{
	/**
	 * @var array<int,array{0:CronExpression,1:string}> $entries
	 */
	#[Inject(name: '#cli.cron')]
	private array $entries;

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->writeln('<info>cron started<info>');

		$app = $this->getApplication();
		assert($app !== null);

		/**
		 * @var CronExpression $cron
		 */
		foreach ($this->entries as [$cron, $command]) {
			if ($cron->isDue()) {
				$app->get($command)
					->run($input, $output);

				$output->writeln("<comment>running {$command}</comment>");
			}
		}

		$output->writeln('<info>cron ended</info>');

		return Command::SUCCESS;
	}
}
