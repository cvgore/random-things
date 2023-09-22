<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([SetList::PSR_12, SetList::SYMPLIFY, SetList::CLEAN_CODE, SetList::COMMON]);
    
    $ecsConfig->paths([__DIR__ . '/src']);

    // indent and tabs/spaces [default: spaces]
    $ecsConfig->indentation('tab');

    // end of line [default: PHP_EOL]; other options: "\n"
    $ecsConfig->lineEnding("\n");
    $ecsConfig->ruleWithConfiguration(\Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer::class, [
        \Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer::LINE_LENGTH => 80,
    ]);
};