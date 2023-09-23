<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Formatter;

use Cvgore\RandomThings\Dto\News;
use Cvgore\RandomThings\Translator\Translator;
use DI\Attribute\Inject;

final readonly class NewsFormatter
{
	#[Inject]
	private Translator $translator;

	/**
	 * @param News[] $news
	 */
	public function format(array $news): string
	{
		if (count($news) === 0) {
			return $this->translator->translate('news.no-data');
		}

		$news = array_filter($news);

		$news = array_map($this->formatSingle(...), $news);
		return implode("\n", $news);
	}

	private function formatSingle(News $news): string
	{
		return sprintf('- [%s](<%s>)', $news->headline, $news->sourceUrl);
	}
}
