<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;

final readonly class HttpClient
{
	private const HEADER_VALUE_JSON = 'application/json';

	private const HEADER_ACCEPT = 'Accept';

	private const HEADER_USER_AGENT = 'User-Agent';

	private const HEADER_CONTENT_TYPE = 'Content-Type';

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
			$response = $this->client->request('GET', $url, [
				RequestOptions::QUERY => $query,
			]);
			$hasExpectedContentType = $this->hasHeader(
				$response,
				self::HEADER_CONTENT_TYPE,
				self::HEADER_VALUE_JSON
			);

			if (! $hasExpectedContentType) {
				return null;
			}

			return json_decode(
				json: (string) $response->getBody(),
				associative: true,
				flags: JSON_THROW_ON_ERROR
			);
		} catch (RequestException|JsonException $ex) {
			return null;
		}
	}

	private function hasHeader(
		ResponseInterface $response,
		string $headerName,
		string $value
	): bool {
		$headers = $response->getHeader(mb_strtolower($headerName));

		if (count($headers) === 0) {
			return false;
		}

		foreach ($headers as $headerValue) {
			$headerValue = trim($headerValue);

			if (str_contains($headerValue, $value)) {
				return true;
			}
		}

		return false;
	}
}
