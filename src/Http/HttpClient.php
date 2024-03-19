<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Http;

use Cvgore\RandomThings\Routing\HttpMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;

final readonly class HttpClient
{
	private const HEADER_ACCEPT = 'Accept';

	private const HEADER_USER_AGENT = 'User-Agent';

	private Client $client;

	/**
	 * @psalm-suppress PossiblyUnusedMethod
	 */
	public function __construct()
	{
		$this->client = new Client([
			RequestOptions::HEADERS => [
				self::HEADER_USER_AGENT => 'random-things/v1.0 (email:random@cvgo.re)',
				self::HEADER_ACCEPT => 'application/json',
			],
		]);
	}

	public function get(string $url, array $query = []): ?array
	{
		try {
			$body = $this->raw(HttpMethod::Get, $url, [
				RequestOptions::QUERY => $query,
			]);

			if ($body?->getStatusCode() !== 200) {
				return null;
			}

			assert($body !== null);

			return json_decode(
				json: (string) $body->getBody(),
				associative: true,
				flags: JSON_THROW_ON_ERROR
			);
		} catch (JsonException) {
			return null;
		}
	}

	public function raw(
		HttpMethod $method,
		string $url,
		array $options = []
	): ?ResponseInterface {
		try {
			return $this->client->request($method->value, $url, $options);
		} catch (RequestException $ex) {
			return $ex->getResponse();
		}
	}
}
