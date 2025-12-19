<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;

class RegisterService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function register(
        User $user,
    ): void {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
    }

    public function update(
        User $user,
    ): void {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
    }
}