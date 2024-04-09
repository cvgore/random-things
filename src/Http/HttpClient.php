<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Http;

use ArrayObject;
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

    private ArrayObject $oneshotConfig;

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
        $this->oneshotConfig = new ArrayObject();
	}

	public function get(string $url, array $query = []): ?array
	{
		try {
			$response = $this->raw(HttpMethod::Get, $url, [
				RequestOptions::QUERY => $query,
                ...$this->oneshotConfig->getArrayCopy(),
			]);
            $this->oneshotConfig->exchangeArray([]);

			if ($response?->getStatusCode() !== 200) {
				return null;
			}

			assert($response !== null);

			return json_decode(
				json: (string) $response->getBody(),
				associative: true,
				flags: JSON_THROW_ON_ERROR
			);
		} catch (JsonException) {
			return null;
		}
	}

    public function post(string $url, array $body): ?array
    {
        try {
            $response = $this->raw(HttpMethod::Post, $url, [
                RequestOptions::JSON => $body,
                ...$this->oneshotConfig->getArrayCopy(),
            ]);

            $this->oneshotConfig->exchangeArray([]);

            if ($response?->getStatusCode() !== 200) {
                return null;
            }

            assert($response !== null);

            return json_decode(
                json: (string) $response->getBody(),
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

    public function withHeader(string $name, string $value): self
    {
        /** @var array<array-key, mixed> $previousValue */
        $previousValue = $this->oneshotConfig->offsetExists(RequestOptions::HEADERS)
            ? $this->oneshotConfig->offsetGet(RequestOptions::HEADERS)
            : [];

        $this->oneshotConfig->offsetSet(
            RequestOptions::HEADERS,
            [
                ...$previousValue,
                $name => $value,
            ]
        );

        return $this;
    }
}
