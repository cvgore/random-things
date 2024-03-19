<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Dto\MapaInternetow;

use DateTimeInterface;

final class Point
{
	public function __construct(
		public int                $id,
		public int                $mapId,
		public string             $title,
		public ?string             $excerpt,
		/**
		 * @psalm-var array{0:int,1:int}
		 * @var int[] [latitude, longitude]
		 */
		public array              $coords,
		public ?bool               $assumedCoords,
		/**
		 * @var Submitter[]
		 */
		public array              $submitters,
		/**
		 * @var Link[]
		 */
		public array              $links,
		public ?string             $icon,
		public ?string             $pinColor,
		/**
		 * @var Tag[]
		 */
		public array              $tags,
		public ?string             $group,
		public DateTimeInterface  $createdAt,
		public ?DateTimeInterface $recordedAt,
		public ?int                $difficulty
	) {
	}
}
