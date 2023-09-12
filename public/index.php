<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

const APP_CTX = 1;

$kernel = new Cvgore\RandomThings\Kernel();
$kernel->initialize();
$kernel->run();