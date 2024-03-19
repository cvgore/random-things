<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Repository;

use Cvgore\RandomThings\Repository\External\YoutubeVideosRepository as ExternalYoutubeVideosRepository;
use DateTimeImmutable;
use DateTimeInterface;
use DI\Attribute\Inject;
use SQLite3;

final class YoutubeVideosRepository
{
	public const DATE_FORMAT = 'Y-m-d H:i:s';

	#[Inject]
	private SQLite3 $db;

	#[Inject]
	private ExternalYoutubeVideosRepository $external;

	public function updateVideoAvailability(string $url): void
	{
		$available = $this->external->isVideoOnline($url);

		$stmt = $this->db->prepare(
			<<<SQL
INSERT INTO yt_videos_availability (url, available_at, unavailable_since)
VALUES (:url, :availableAt, :unavailableSince)
ON CONFLICT(url) DO UPDATE SET available_at=:availableAt, unavailable_since=:unavailableSince;
SQL
		);

		$time = (new DateTimeImmutable())->format(self::DATE_FORMAT);

		$stmt->bindParam('url', $url);
		if (! $available) {
			$stmt->bindValue('unavailableSince', $time);
			$stmt->bindValue('availableAt', null);
		} else {
			$stmt->bindValue('availableAt', $time);
			$stmt->bindValue('unavailableSince', null);
		}

		$stmt->execute();
	}

	/**
	 * @return string[]
	 */
	public function getUnavailableVideos(?DateTimeInterface $since): array
	{
		if ($since !== null) {
			$stmt = $this->db->prepare(
				<<<SQL
SELECT url
FROM yt_videos_availability
WHERE unavailable_since IS NOT NULL
AND unavailable_since > :since
SQL
			);

			$stmt->bindValue('since', $since->format(self::DATE_FORMAT));
		} else {
			$stmt = $this->db->prepare(
				<<<SQL
SELECT url
FROM yt_videos_availability
WHERE unavailable_since IS NOT NULL
SQL
			);
		}

		$result = $stmt->execute();

		$set = [];
		while ($row = $result->fetchArray(SQLITE3_NUM)) {
			$set[] = $row[0];
		}
		$result->finalize();

		return $set;
	}
}
