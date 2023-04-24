<?php

namespace App\Action\Home;

use Nyholm\Psr7\Response as Response;
use Nyholm\Psr7\ServerRequest as Request;

final class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write('Hello World!');
        return $response;
    }
}