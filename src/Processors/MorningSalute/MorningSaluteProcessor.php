<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

interface MorningSaluteProcessor
{
    public function getPlaceholder(): string;

    public function generate(): string;
}