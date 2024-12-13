<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

use Cvgore\RandomThings\Repository\External\NameDaysRepository;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class NameDaysProcessor implements MorningSaluteProcessor
{
    #[Inject]
    private NameDaysRepository $nameDaysRepository;

    #[Inject]
    private Translator $translator;

    public function getPlaceholder(): string
    {
        return '@nameDays';
    }

    public function generate(): string
    {
        $nameDays = $this->nameDaysRepository->getRandomNameDaysForToday();
        return $nameDays
            ? implode(',', $nameDays)
            : $this->translator->translate('namedays.no-data');
    }
}