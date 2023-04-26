<?php

namespace App\Domain\JOTC\Service;

use App\Domain\JOTC\Repository\JOTCRepository;


final class JOTCFinderService
{

    private JOTCRepository $JOTCRepository;

    public function __construct(JOTCRepository $JOTCRepository)
    {
        $this->JOTCRepository = $JOTCRepository;
    }

    public function findSubmissions(array $params){
        $res = $this->JOTCRepository->getSubmissions($params);
        return $res;
    }

}