<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Database;

use Cvgore\RandomThings\Generator\PathGenerator;
use DI\Attribute\Inject;
use FilesystemIterator;
use Generator;
use SplFileInfo;
use SQLite3;

final class Migrator
{
	#[Inject]
	private PathGenerator $pathGenerator;

	#[Inject]
	private SQLite3 $db;

	/**
	 * @return Generator<array-key, string>
	 */
	public function run(): Generator
	{
		$alreadyRun = $this->getAlreadyRunMigrations();

		foreach ($this->getMigrationsIterator() as $path) {
			if (! $path->isFile() || $path->getExtension() !== 'php') {
				continue;
			}

			$migrationName = $path->getBasename('.php');

			if (in_array($migrationName, $alreadyRun, true)) {
				continue;
			}

			$this->runMigration($path->getRealPath(), $migrationName);
			yield $migrationName;
		}
	}

	/**
	 * @return SplFileInfo[]
	 */
	private function getMigrationsIterator(): array
	{
		/** @var SplFileInfo[] $files */
		$files = iterator_to_array(new FilesystemIterator(
			$this->pathGenerator->getRootPath('src/Database/Migrations')
		));

		usort(
			$files,
			fn (SplFileInfo $a, SplFileInfo $b) => (int) $a->getBasename(
				'.php'
			) - (int) $b->getBasename('.php')
		);

		return $files;
	}

	private function runMigration(string $path, string $migrationName): void
	{
		/** @var MigrationInterface $class */
		$class = require $path;

		$this->db->exec('BEGIN TRANSACTION');
		$this->db->exec($class->up());

		$stmt = $this->db->prepare('INSERT INTO migrations ("id") VALUES (:id)');
		$stmt->bindValue('id', $migrationName);
		$stmt->execute();
		$this->db->exec('COMMIT');
	}

	/**
	 * @return string[]
	 */
	private function getAlreadyRunMigrations(): array
	{
		$migrationsExists = $this->db->querySingle(
			"SELECT 1 FROM sqlite_master WHERE name='migrations'"
		) === 1;

		if (! $migrationsExists) {
			return [];
		}

		$migrations = [];
		$result = $this->db->query('SELECT id FROM migrations');
		while ($data = $result->fetchArray(SQLITE3_NUM)) {
			$migrations[] = $data[0];
		}
		$result->finalize();

		return $migrations;
	}
}
