<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use Cvgore\RandomThings\Dto\Taunt;
use DI\Attribute\Inject;
use SQLite3;
use Symfony\Component\Uid\Uuid;

final class MordaTauntsRepository
{
	#[Inject]
	private SQLite3 $db;

	public function addTaunt(Taunt $taunt, Uuid $batchId): void
	{
		$stmt = $this->db->prepare(
			<<<SQL
INSERT INTO morda_taunts (id, batch_id, taunt, score)
VALUES (:id, :batch_id, :taunt, :score);
SQL
		);
		$stmt->bindValue('id', $taunt->id);
		$stmt->bindValue('batch_id', $batchId->toRfc4122());
		$stmt->bindValue('taunt', $taunt->text);
		$stmt->bindValue('score', 0);

		$stmt->execute();
	}

	public function setTauntScore(string $tauntId, int $score): void
	{
		$stmt = $this->db->prepare(
			<<<SQL
UPDATE morda_taunts
SET score = :score
WHERE id = :id;
SQL
		);
		$stmt->bindValue('id', $tauntId);
		$stmt->bindValue('score', $score);

		$stmt->execute();
	}
}
