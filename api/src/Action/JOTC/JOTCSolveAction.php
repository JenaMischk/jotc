<?php

namespace App\Action\JOTC;

use Nyholm\Psr7\Response as Response;
use Nyholm\Psr7\ServerRequest as Request;
use App\Domain\JOTC\Service\JOTCSolverService;


final class JOTCSolveAction
{

    private JOTCSolverService $jotcSolverService;

    public function __construct(JOTCSolverService $jotcSolverService)
    {
        $this->jotcSolverService = $jotcSolverService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $result = $this->jotcSolverService->solve($data);
        $response->getBody()->write(json_encode($result));
        $response = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
        return $response;
    }
}