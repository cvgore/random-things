<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External\Discord;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;

class ChannelsRepository
{
	#[Inject(name: 'discord.url')]
	private string $baseUrl;

	#[Inject(name: 'discord.api_key')]
	private string $apiKey;

	#[Inject]
	private HttpClient $client;

	public function createPrivateMessageChannel(string $userId): ?string
	{
		$body = $this->client
			->withHeader('Authorization', 'Bot ' . $this->apiKey)
			->post("{$this->baseUrl}/users/@me/channels", [
				'recipient_id' => $userId,
			]);

		if ($body === null) {
			return null;
		}

		assert(array_key_exists('id', $body));

		return $body['id'];
	}

	public function sendMessageToChannel(string $channelId, string $body): void
	{
		$this->client
			->withHeader('Authorization', 'Bot ' . $this->apiKey)
			->post("{$this->baseUrl}/channels/{$channelId}/messages", [
				'content' => $body,
			]);
	}
}
