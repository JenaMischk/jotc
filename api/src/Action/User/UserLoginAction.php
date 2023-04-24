<?php

namespace App\Action\User;

use Nyholm\Psr7\Response as Response;
use Nyholm\Psr7\ServerRequest as Request;
use App\Domain\User\Service\UserLoginService;


final class UserLoginAction
{

    private UserLoginService $userLoginService;

    public function __construct(UserLoginService $userLoginService)
    {
        $this->userLoginService = $userLoginService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $result = $this->userLoginService->loginUser($data);
        $response->getBody()->write(json_encode($result));
        $response = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
        return $response;
    }
}