<?php

namespace App\Domain\JOTC\Service;


final class JOTCSolverService
{

    public function __construct()
    {

    }

    public function solve(array $input)
    {

        $input = $input['clouds'];

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