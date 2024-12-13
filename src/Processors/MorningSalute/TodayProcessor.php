<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

use Cvgore\RandomThings\Provider\CurrentDateProvider;
use DI\Attribute\Inject;

final readonly class TodayProcessor implements MorningSaluteProcessor
{
    #[Inject]
    private CurrentDateProvider $currentDateProvider;

    public function getPlaceholder(): string
    {
        return '@today';
    }

    public function generate(): string
    {
        return $this->currentDateProvider->todayLong();
    }
}