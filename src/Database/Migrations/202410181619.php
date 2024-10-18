<?php

declare(strict_types=1);

return new class() implements \Cvgore\RandomThings\Database\MigrationInterface {
	public function up(): string
	{
		return <<<SQL
CREATE TABLE cache_storage
(
    key TEXT PRIMARY KEY,
    value TEXT NOT NULL,
    ttl TIMESTAMP NULL
);
CREATE VIEW cache AS
SELECT key, value FROM cache_storage
WHERE ttl IS NULL OR ttl >= CURRENT_TIMESTAMP;

CREATE TRIGGER tr_cache_cleanup
    AFTER INSERT ON cache_storage
BEGIN
    DELETE FROM cache_storage WHERE ttl < CURRENT_TIMESTAMP AND ttl IS NOT NULL;
END;
SQL;
	}
};
