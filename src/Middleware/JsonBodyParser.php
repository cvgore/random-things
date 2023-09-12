<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Middleware;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ReflectionFunction;
use ReflectionNamedType;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class JsonBodyParser implements MiddlewareInterface
{
    #[Inject]
    private SerializerInterface $serializer;
    
    public function process(Request $request, RequestHandler $handler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if ($request->getMethod() !== 'GET' && $contentType === 'application/json') {
            $routeCtx = RouteContext::fromRequest($request);
            $controller = $routeCtx->getRoute()->getCallable();
            $function = new ReflectionFunction($controller);
            
            if ($function->getNumberOfParameters() === 3) {
                $contents = trim(file_get_contents('php://input'));
                if ($contents === '') {
                    throw new HttpBadRequestException($request, 'expected body');
                }
                
                [,,$arg] = $function->getParameters();
                $type = $arg->getType();
                
                if ($type instanceof ReflectionNamedType) {
                    $deserialized = $this->serializer->deserialize($contents, $type->getName(), 'json');
                    
                    $routeCtx->getRoute()->setArgument($arg->getName(), $deserialized);
                    $request = $request->withParsedBody($deserialized);
                }
            }

        }

        return $handler->handle($request);
    }
}