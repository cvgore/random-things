<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

use Cvgore\RandomThings\Repository\External\CalendarRepository;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class DayOfProcessor implements MorningSaluteProcessor
{
    #[Inject]
    private CalendarRepository $calendarRepository;

    #[Inject]
    private Translator $translator;

    public function getPlaceholder(): string
    {
        return '@dayOf';
    }

    public function generate(): string
    {
        return $this->calendarRepository->getRandomCalendarDay()
            ?? $this->translator->translate('calendar.no-data');
    }
}