<?php

namespace App\Domain\User\Service;

use App\Persistency\CacheService;


final class UserValidator
{

    private CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function validateUser(array $data)
    {       

        $result = [
            'email' => isset($data['email']) ? $this->validateEmail($data['email']) : 'Email address is mandatory',
            'firstName' => isset($data['firstName']) ? $this->validateLength($data['firstName'], 3, 30, 'First Name') : 'First name is mandatory',
            'lastName' => isset($data['lastName']) ? $this->validateLength($data['lastName'], 3, 30, 'Last Name') : 'Last name is mandatory',
            'birthDate' => isset($data['birthDate']) ? $this->validateDate($data['birthDate']) : 'Birth Date is mandatory',
        ];

        $result = array_filter($result);

        if($result){
            throw new \App\Exception\ValidationException(json_encode($result));
        }

        return $result;
    }

    private function validateLength(string $input, int $min, int $max, string $fieldName = '')
    {
        if( strlen($input) <= $min){
            return "$fieldName is too short";
        }
        if( strlen($input) > $max){
            return "$fieldName is too long";
        }
        return false;
    }

    private function validateDate(string $date)
    {
        if( strtotime($date) ){
            return false;
        }
        return 'Invalid date';
    }

    private function validateEmail(string $email)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address';
        }

        $key = hash('xxh128', "email_validation_$email");

        if(!$result = $this->cache->read($key)){
            $result = file_get_contents("https://api.emailable.com/v1/verify?email=$email&api_key=test_2215ca54dd1a4942390b");
            $this->cache->write($key, $result);
        }
       
        $reason = json_decode($result)->reason;
        $state = json_decode($result)->state;

        if( $reason === 'accepted_email' && $state === 'deliverable'){
            return false;
        }

        return 'Invalid email address';
    }

}