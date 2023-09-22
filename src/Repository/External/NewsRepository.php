<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Dto\News;
use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;
use Random\Randomizer;

final readonly class NewsRepository
{
	#[Inject(name: 'news_api.url')]
	private string $baseUrl;

	#[Inject(name: 'news_api.api_key')]
	private string $apiKey;

	#[Inject(name: 'news_api.domains_whitelist')]
	private string $domainsWhitelist;

	#[Inject(name: 'news_api.domain_priority')]
	private string $domainPriority;

	#[Inject(name: 'news_api.category')]
	private string $category;

	#[Inject(name: 'news_api.language')]
	private string $language;

	#[Inject(name: 'news_api.limit')]
	private int $newsLimit;

	#[Inject]
	private Randomizer $randomizer;

	#[Inject]
	private HttpClient $client;

	/**
	 * @return News[]
	 */
	public function getRandomTopNews(): array
	{
		$body = $this->client->get("{$this->baseUrl}/api/1/news", [
			'apikey' => $this->apiKey,
			'language' => $this->language,
			'category' => $this->category,
			'prioritydomain' => $this->domainPriority,
			'domainurl' => $this->domainsWhitelist,
		]);

		if ($body === null) {
			return [];
		}

		assert(array_key_exists('status', $body));

		if ($body['status'] !== 'success') {
			return [];
		}

		assert(array_key_exists('results', $body));
		assert(is_array($body['results']));

		$news = $body['results'];
		$news = $this->randomizer->shuffleArray($news);
		$news = array_slice($news, 0, $this->newsLimit);

		return array_map($this->mapNews(...), $news);
	}

	private function mapNews(array $item): News
	{
		assert(array_key_exists('title', $item));
		assert(array_key_exists('link', $item));

		return new News(headline: $item['title'], sourceUrl: $item['link']);
	}
}
