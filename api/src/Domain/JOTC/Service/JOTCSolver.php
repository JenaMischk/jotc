<?php

namespace App\JOTC;


final class JOTCSolver
{

    public function __construct()
    {

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
        
        for($cloudIndex = 0; $cloudIndex < $target;) {
            
            $oneStepIndex = $cloudIndex + 1;
            $twoStepIndex = $cloudIndex + 2;
            
            if ( isset($input[$twoStepIndex]) ) {
                $cloudIndex = $twoStepIndex;
                $count++;
                continue;
            }
                    
            if ( isset($input[$oneStepIndex]) ) {
                $cloudIndex = $oneStepIndex;
                $count++;
            }
            
        }
        
        return $count;
    }

}