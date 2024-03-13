<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DateTime;
use DI\Attribute\Inject;
use Random\Randomizer;

final readonly class CalendarRepository
{
	#[Inject(name: 'calendar.url')]
	private string $baseUrl;
	#[Inject]
	private Randomizer $random;

	#[Inject]
	private HttpClient $client;

	public function getRandomCalendarDay(): ?string
	{
        $date = new DateTime();
        $month = $date->format('n');
        $day = $date->format('j');

		$body = $this->client->get(
			"{$this->baseUrl}/{$month}/{$day}.json",
		);

		if ($body === null) {
			return null;
		}

		assert(is_array($body));
		assert(array_is_list($body));

        [$key] = $this->random->pickArrayKeys($body, 1);

        assert(array_key_exists($key, $body));
        assert(array_key_exists('name', $body[$key]));

        return $body[$key]['name'];
	}
}
