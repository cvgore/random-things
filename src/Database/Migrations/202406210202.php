<?php

declare(strict_types=1);

return new class() implements \Cvgore\RandomThings\Database\MigrationInterface {
	public function up(): string
	{
		return <<<SQL
CREATE TABLE morda_taunts
(
    id TEXT PRIMARY KEY,
    batch_id TEXT NOT NULL,
    taunt TEXT NOT NULL,
    score INT NOT NULL
);
SQL;
	}
};
