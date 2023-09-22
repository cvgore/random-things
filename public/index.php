<?php /** @noinspection PhpDefineCanBeReplacedWithConstInspection */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

define('APP_DEFINE_GUARD', 1);

$kernel = new Cvgore\RandomThings\Kernel();
$kernel->initialize();
$kernel->run();