<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use DateTimeImmutable;
use DI\Attribute\Inject;
use Psr\SimpleCache\CacheInterface;
use SQLite3;
use stdClass;

final readonly class CacheRepository implements CacheInterface
{
	#[Inject]
	private SQLite3 $db;

	public function get(string $key, mixed $default = null): mixed
	{
		$stmt = $this->db->prepare(
			<<<SQL
SELECT value FROM cache WHERE key = ?
SQL
		);
		$stmt->bindValue(1, $key);
		$result = $stmt->execute();
		$data = $result->fetchArray();

		if (! $data) {
			return $default;
		}

		return $data['value'];
	}

	public function set(
		string $key,
		mixed $value,
		\DateInterval|int|null $ttl = null
	): bool {
		$stmt = $this->db->prepare(
			<<<SQL
INSERT OR REPLACE INTO cache_storage
          VALUES (?, ?, ?)
SQL
		);
		$stmt->bindValue(1, $key);
		$stmt->bindValue(2, $value);

		if ($ttl === null) {
			$stmt->bindValue(3, null);
		} else {
			$time = is_int($ttl)
				? (new DateTimeImmutable())->modify("+{$ttl} seconds")
				: (new DateTimeImmutable())->add($ttl);
			assert($time !== false);
			$stmt->bindValue(3, $time->getTimestamp());
		}

		return $stmt->execute() !== false;
	}

	public function delete(string $key): bool
	{
		$stmt = $this->db->prepare(<<<SQL
DELETE FROM cache WHERE key = ?
SQL
		);
		$stmt->bindValue(1, $key);
		return $stmt->execute() !== false;
	}

	public function clear(): bool
	{
		return $this->db->exec(<<<SQL
DELETE FROM cache
SQL
		);
	}

	public function getMultiple(iterable $keys, mixed $default = null): iterable
	{
	}

	public function setMultiple(
		iterable $values,
		\DateInterval|int|null $ttl = null
	): bool {
	}

	public function deleteMultiple(iterable $keys): bool
	{
	}

	public function has(string $key): bool
	{
		$nullPtr = new stdClass();

		return $this->get($key, $nullPtr) !== $nullPtr;
	}
}
