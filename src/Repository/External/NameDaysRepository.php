<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;
use Random\Randomizer;

final readonly class NameDaysRepository
{
	#[Inject(name: 'namedays.url')]
	private string $baseUrl;

	#[Inject(name: 'namedays.limit')]
	private int $namesLimit;

	#[Inject(name: 'namedays.country')]
	private string $country;

	#[Inject(name: 'namedays.timezone')]
	private string $timezone;

	#[Inject]
	private Randomizer $randomizer;

	#[Inject]
	private HttpClient $client;

	/**
	 * @return string[]
	 */
	public function getRandomNameDaysForToday(): array
	{
		$body = $this->client->get(
			"{$this->baseUrl}/api/V1/today",
			[
				'country' => $this->country,
				'timezone' => $this->timezone,
			]
		);

		if ($body === null) {
			return [];
		}

		assert(array_key_exists('nameday', $body));
		assert(array_key_exists('pl', $body['nameday']));

		$line = $body['nameday']['pl'];
		if ($line === null) {
			return [];
		}

		$line = trim($line);
		if ($line === '' || $line === 'n/a') {
			return [];
		}

		$entries = explode(',', $line);
		$entries = array_map(trim(...), $entries);
		$entries = $this->randomizer->shuffleArray($entries);

		return array_slice($entries, 0, $this->namesLimit);
	}
}
