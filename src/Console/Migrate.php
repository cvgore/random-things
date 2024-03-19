<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Console;

use Cvgore\RandomThings\Database\Migrator;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'migrate', description: 'runs database migrations')]
final class Migrate extends Command
{
	#[Inject]
	private Migrator $migrator;

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$migrationsRun = $this->migrator->run();

		foreach ($migrationsRun as $name) {
			$output->writeln("<comment>migration {$name} ran</comment>");
		}

		$output->writeln('<info>all migrations have ran</info>');

		return Command::SUCCESS;
	}
}
