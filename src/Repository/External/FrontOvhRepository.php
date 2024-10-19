<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

final readonly class FrontOvhRepository
{
	#[Inject(name: 'ovh.front.url')]
	private string $baseUrl;

	#[Inject]
	private HttpClient $client;

	/**
	 * @return string[]
	 */
	public function isAvailableServer(): array
	{
		$body = $this->client
			->get("{$this->baseUrl}/dedicated/server/datacenter/availabilities", [
				'excludeDatacenters' => 'false',
				'planCode' => '24ska01',
				'server' => '24ska01',
			]);

		/* [{
				"fqn": "24ska01.ram-64g-noecc-2133.softraid-1x480ssd",
				"memory": "ram-64g-noecc-2133",
				"planCode": "24ska01",
				"server": "24ska01",
				"storage": "softraid-1x480ssd",
				"datacenters": [{
					"availability": "unavailable",
					"datacenter": "bhs"
				}, {
					"availability": "unavailable",
					"datacenter": "fra"
				}, {
					"availability": "unavailable",
					"datacenter": "gra"
				}, {
					"availability": "unavailable",
					"datacenter": "lon"
				}, {
					"availability": "unavailable",
					"datacenter": "rbx"
				}, {
					"availability": "unavailable",
					"datacenter": "sbg"
				}, {
					"availability": "unavailable",
					"datacenter": "waw"
				}]
		  }] */

		if ($body === null) {
			return [];
		}

		assert(array_is_list($body));
		assert(count($body) === 1);
		assert(is_array($body[0]));
		assert(array_key_exists('datacenters', $body[0]));
		assert(array_is_list($body[0]['datacenters']));

		$availableInDatacenters = array_filter(
			$body[0]['datacenters'],
			function (array $data) {
				return $data['availability'] !== 'unavailable';
			}
		);
		$availableInDatacenters = array_values($availableInDatacenters);

		if (count($availableInDatacenters) === 0) {
			return [];
		}

		return array_column($availableInDatacenters, 'datacenter');
	}
}
