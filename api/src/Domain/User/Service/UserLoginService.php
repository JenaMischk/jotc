<?php

namespace App\Domain\User\Service;

use App\Domain\User\Service\UserValidator;
use App\Domain\User\Repository\UserRepository;


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
        return $user;
    }

}