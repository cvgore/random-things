<?php

declare(strict_types=1);

return new class() implements \Cvgore\RandomThings\Database\MigrationInterface {
	public function up(): string
	{
		return <<<SQL
CREATE TABLE yt_videos_availability
(
    url TEXT PRIMARY KEY,
    available_at DATETIME NULL,
    unavailable_since DATETIME NULL
);
SQL;
	}
};
