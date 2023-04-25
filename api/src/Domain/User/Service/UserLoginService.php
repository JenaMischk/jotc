<?php

namespace App\Domain\User\Service;

use App\Domain\User\Service\UserValidator;
use App\Domain\User\Repository\UserRepository;
use Firebase\JWT\JWT;


final class UserLoginService
{

    private UserValidator $userValidator;
    private UserRepository $userRepository;

    public function __construct(UserValidator $userValidator, UserRepository $userRepository)
    {
        $this->userValidator = $userValidator;
        $this->userRepository = $userRepository;
    }

    public function loginUser(array $data)
    {
        $this->userValidator->validateUser($data);
        $user = $this->userRepository->getUser($data);
        if(!$user){
            $user = $this->userRepository->createUser($data);
        }

        $time = time();
        $expiresAt = $time + 3600;
        $payload = [
            'iss' => 'jotc',
            'aud' => 'jotc',
            'iat' => $time,
            'nbf' => $time,
            'exp' => $expiresAt,
            'userId' => $user['id']
        ];

        $key = getenv('JWT_SECRET_KEY') ? getenv('JWT_SECRET_KEY') : 'example_key';
        $jwt = JWT::encode($payload, $key, 'HS256');

        $result = [
            'expiresAt' => $expiresAt,
            'token' => $jwt
        ];

        return $result;
    }

}