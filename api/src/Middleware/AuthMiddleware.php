<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

/**
 * CORS middleware.
 */
final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $route = $request->getUri()->getPath();
        if(in_array($route, ['/', '/login']) || $request->getMethod() === 'OPTIONS'){
            return $handler->handle($request);
        }

        try{

            $token = $request->getHeader('Authorization');
            if(!isset($token[0])){
                throw new \Exception('Could not find a valid Authentication header');
            }
            $token = substr($token[0], 7);

            $key = getenv('JWT_SECRET_KEY') ? getenv('JWT_SECRET_KEY') : 'example_key';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

        } catch(\Exception $e){
            return new \Nyholm\Psr7\Response(401);
        }

        $userId = $decoded->userId;

        $request = $request->withAttribute('userId', $userId);

        return $handler->handle($request);

    }
}