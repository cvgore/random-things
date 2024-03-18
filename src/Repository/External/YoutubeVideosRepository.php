<?php

namespace Cvgore\RandomThings\Repository\External;

use Cvgore\RandomThings\Http\HttpClient;
use DI\Attribute\Inject;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

final class YoutubeVideosRepository
{
    #[Inject(name: 'random_yt_movie.tries')]
    private int $tries;

    #[Inject(name: 'random_yt_movie.url')]
    private string $baseUrl;

    #[Inject(name: 'random_yt_movie.yt_url')]
    private string $ytUrl;

    #[Inject]
    private HttpClient $client;

    public function getRandomVideoUrl(): ?string
    {
        $randomizerEngine = new Xoshiro256StarStar(
            (int)(new \DateTimeImmutable())->format('Ymd')
        );
        $random = new Randomizer($randomizerEngine);

        $mapsData = $this->client->get($this->baseUrl);
        assert(is_array($mapsData));
        assert(array_key_exists('points', $mapsData));
        assert(array_is_list($mapsData['points']));

        for ($_ = 0; $_ < $this->tries; $_++) {
            [$key] = $random->pickArrayKeys($mapsData['points'], 1);

            assert(is_array($mapsData['points'][$key]));
            assert(array_key_exists('links', $mapsData['points'][$key]));
            assert(array_is_list($mapsData['points'][$key]['links']));

            $links = $mapsData['points'][$key]['links'];
            $links = array_filter($links, fn(array $link) => $link['type'] === 'yt');

            foreach ($links as $link) {
                if ($this->isVideoOnline($link['url'])) {
                    return $link['url'];
                }
            }

            unset($mapsData['points'][$key]);
        }

        return null;
    }

    public function isVideoOnline(string $url): bool
    {
        return $this->client->get($this->ytUrl, [
            'url' => $url,
            'format' => 'json'
        ]) !== null;
    }
}