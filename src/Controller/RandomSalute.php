<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Controller;

use Cvgore\RandomThings\Dto\RandomSaluteRequest;
use Cvgore\RandomThings\Dto\RandomSaluteResponse;
use DI\Attribute\Inject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\Serializer\SerializerInterface;

final readonly class RandomSalute implements ControllerInterface
{
    #[Inject]
    private SerializerInterface $serializer;
    #[Inject(name: "giphy_url")]
    private string $giphyUrl;
    #[Inject(name: "giphy_apikey")]
    private string $giphyApiKey;
    
    public function getRoutePattern(): string
    {
        return '/v1/random/salute';
    }
    
    public function handle(Request $request, Response $response, RandomSaluteRequest $data): Response
    {
        $salute = json_decode(file_get_contents(__DIR__ . '/../../resources/salute.json'), true);
        $salute = $salute['categories'];
        
        if (!array_key_exists($data->category, $salute)) {
            return $response
                ->withStatus(404);
        }
        
        $salute = $salute[$data->category];
        $gifTag = urlencode($salute['gifTag']);
        $salute = $salute['entries'];
        $itemId = random_int(0, count($salute) - 1);
        
        $body = new RandomSaluteResponse();
        $body->salute = $salute[$itemId];
        $body->id = "$itemId";
        
        $client = new Client();
        try {
            $_response = $client->request(
                "GET",
                "{$this->giphyUrl}/v1/gifs/random?api_key={$this->giphyApiKey}&tag={$gifTag}"
            );
            
            $body->gifUrl = json_decode($_response->getBody()->__toString(), true)["data"]['embed_url'];
        } catch (GuzzleException) {
            $body->gifUrl = "";
        }
        
        $response->getBody()->write($this->serializer->serialize($body, 'json'));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}