#!/usr/bin/env php
<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    /** @psalm-suppress ForbiddenCode */
    echo '! This must be run from cli interface !' . PHP_EOL;
    exit(2);
}

require __DIR__ . '/vendor/autoload.php';

define('APP_DEFINE_GUARD', 1);

$kernel = new Cvgore\RandomThings\Kernel();
$kernel->run(new Cvgore\RandomThings\Runtime\CliRuntime());

