<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\User;
use App\Repository\UserRepository;

class RegisterService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $repository
    ) {}

    public function register(User $user)
    {
        if ($this->repository->findOneBy(['email' => $user->getEmail()])) {
            throw new BadRequestHttpException('Email already exists.');
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
    }
}