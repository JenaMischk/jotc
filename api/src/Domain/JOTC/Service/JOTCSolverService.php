<?php

namespace App\Domain\JOTC\Service;

use App\Persistency\CacheService;
use App\Domain\JOTC\Repository\JOTCRepository;


final class JOTCSolverService
{

    private CacheService $cache;
    private JOTCRepository $JOTCRepository;

    public function __construct(CacheService $cache, JOTCRepository $JOTCRepository)
    {
        $this->cache = $cache;
        $this->JOTCRepository = $JOTCRepository;
    }

    public function getSolution(array $input, int $userId){

        $input = $input['clouds'];

        $key = implode(',', $input);
        $key = hash('xxh128', "jotc_solution_$key");

        $res = [];
        if(!$res = json_decode($this->cache->read($key))){
            $res = $this->solve($input);
            $this->cache->write($key, json_encode($res));
        }

        $submission = [
            'userId' => $userId,
            'input' => json_encode($input),
            'output' => json_encode($res)
        ];
        $this->JOTCRepository->createSubmission($submission);

        return $res;

    }

    public function solve(array $input)
    {

        foreach ($input as $cloudIndex => $cloudType) {
            if ($cloudType === 1) {
                unset($input[$cloudIndex]);
            }
        }
        
        $target = array_key_last($input);    
        $count = 0;

        $res = [];
        
        for($cloudIndex = 0; $cloudIndex < $target;) {
            
            $oneStepIndex = $cloudIndex + 1;
            $twoStepIndex = $cloudIndex + 2;
            
            if ( isset($input[$twoStepIndex]) ) {
                $res['moves'][] = [
                    'from' => $cloudIndex,
                    'to' => $twoStepIndex
                ];
                $cloudIndex = $twoStepIndex;
                $count++;
                continue;
            }
                    
            if ( isset($input[$oneStepIndex]) ) {
                $res['moves'][] = [
                    'from' => $cloudIndex,
                    'to' => $oneStepIndex
                ];
                $cloudIndex = $oneStepIndex;
                $count++;
                continue;
            }

            $res['moves'][] = [
                'from' => $cloudIndex,
                'to' => 'N.A.'
            ];
            $res['total'] = 0;

            return $res;
            
        }

        $res['total'] = $count;
        
        return $res;
    }

}