<?php

return function (Slim\App $app) {
    
    /*$pdo = function(App\Factory\DatabaseFactory $databaseFactory){
        return $databaseFactory->getPDO();
    };
    
    /*$app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        "path" => "/",
        "realm" => "Protected",
        "authenticator" => new \Tuupola\Middleware\HttpBasicAuthentication\PdoAuthenticator([
            "pdo" => $pdo,
            "table" => "users",
        ])
    ]));*/

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

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

    // Handle exceptions
    $app->addErrorMiddleware(true, true, true);
};