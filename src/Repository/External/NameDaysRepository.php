<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class NameDaysRepository
{
	#[Inject(name: 'namedays.url')]
	private string $baseUrl;

	#[Inject]
	private HttpClient $client;

	/**
	 * @return string[]
	 */
	public function getNameDaysForToday(): array
	{
		$body = $this->client->get(
			"{$this->baseUrl}/api/V1/today",
			[
				'country' => 'pl',
				'timezone' => 'Europe/Warsaw',
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

		return explode(',', $line);
	}
}
