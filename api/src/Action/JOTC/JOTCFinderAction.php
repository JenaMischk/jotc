<?php

namespace App\Action\JOTC;

use Nyholm\Psr7\Response as Response;
use Nyholm\Psr7\ServerRequest as Request;
use App\Domain\JOTC\Service\JOTCFinderService;


final class JOTCFinderAction
{

    private JOTCFinderService $jotcFinderService;

    public function __construct(JOTCFinderService $jotcFinderService)
    {
        $this->jotcFinderService = $jotcFinderService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams() ? $request->getQueryParams() : [];
        $result = $this->jotcFinderService->findSubmissions($params);
        $response->getBody()->write(json_encode($result));
        $response = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
        return $response;
    }
}