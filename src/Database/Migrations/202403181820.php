<?php

declare(strict_types=1);

return new class() implements \Cvgore\RandomThings\Database\MigrationInterface {
	public function up(): string
	{
		return <<<SQL
CREATE TABLE migrations
(
    id TEXT PRIMARY KEY
);
SQL;
	}
};
