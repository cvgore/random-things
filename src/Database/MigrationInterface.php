<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Database;

interface MigrationInterface
{
	public function up(): string;
}
