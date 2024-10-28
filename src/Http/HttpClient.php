<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Http;

use ArrayObject;
use Cvgore\RandomThings\Routing\HttpMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Sentry\Tracing\GuzzleTracingMiddleware;

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
		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());
		$stack->push(GuzzleTracingMiddleware::trace());
		$stack->push(Middleware::httpErrors());

		$this->client = new Client([
			RequestOptions::HEADERS => [
				self::HEADER_USER_AGENT => 'random-things/v1.0 (email:random@cvgo.re)',
				self::HEADER_ACCEPT => 'application/json',
			],
            RequestOptions::HTTP_ERRORS => true,
			'handler' => $stack,
            RequestOptions::TIMEOUT => 30,
            RequestOptions::CONNECT_TIMEOUT => 30,
            RequestOptions::READ_TIMEOUT => 30
		]);
		$this->oneshotConfig = new ArrayObject();
	}

	public function get(string $url, array $query = []): ?array
	{
		$response = $this->raw(HttpMethod::Get, $url, [
			RequestOptions::QUERY => $query,
			...$this->oneshotConfig->getArrayCopy(),
		]);
		$this->oneshotConfig->exchangeArray([]);

		return json_decode(
			json: (string) $response->getBody(),
			associative: true,
			flags: JSON_THROW_ON_ERROR
		);
	}

	public function post(string $url, array $body): ?array
	{
		$response = $this->raw(HttpMethod::Post, $url, [
			RequestOptions::JSON => $body,
			...$this->oneshotConfig->getArrayCopy(),
		]);

		$this->oneshotConfig->exchangeArray([]);

		assert($response !== null);

		return json_decode(
			json: (string) $response->getBody(),
			associative: true,
			flags: JSON_THROW_ON_ERROR
		);
	}

	public function raw(
		HttpMethod $method,
		string $url,
		array $options = []
	): ?ResponseInterface {
		return $this->client->request($method->value, $url, $options);
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
