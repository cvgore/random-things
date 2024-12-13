<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

// todo: in progress work
final readonly class CurrencyRepository
{
	#[Inject(name: 'currency_api.url')]
	private string $baseUrl;

	#[Inject]
	private HttpClient $client;

    // note: should work for PLN/USD/EUR but JPY seems to be invalid
	public function convertAmount(string $baseCurrency, string $targetCurrency, float $value): ?float
	{
		$body = $this->client->get("{$this->baseUrl}/{$baseCurrency}.json");

		if ($body === null) {
			return null;
		}

		assert(is_array($body));
        assert(array_key_exists($baseCurrency, $body));

        $rates = $body[$baseCurrency];

        assert(array_key_exists($targetCurrency, $rates));

        $rate = $rates[$targetCurrency];

        return round($value * $rate, 2);
	}
}
