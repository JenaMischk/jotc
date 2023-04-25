<?php

return function (Slim\App $app) {

    //Handle ValidationException
    $app->add(
        function (
            Psr\Http\Message\ServerRequestInterface $request, 
            Psr\Http\Server\RequestHandlerInterface $handler
        ) {
            try {
                return $handler->handle($request);
            } catch (App\Exception\ValidationException $validationException) {

                $response = (new Nyholm\Psr7\Response())
                    ->withHeader('Content-type', 'application/json')    
                    ->withStatus(422);
                $response->getBody()->write($validationException->getMessage());
        
                return $response;
            }
        }
    );

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    $app->add(\App\Middleware\CorsMiddleware::class);

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Handle exceptions
    $app->addErrorMiddleware(true, true, true);
};