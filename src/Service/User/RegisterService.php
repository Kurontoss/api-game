<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Exception\EmailAlreadyRegisteredException;
use App\Repository\UserRepository;

class RegisterService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepo,
    ) {}

    public function register(
        User $user,
    ): void {
        if ($this->userRepo->findOneBy(['email' => $user->getEmail()])) {
            throw new EmailAlreadyRegisteredException();
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
    }

    public function update(
        User $user,
    ): void {
        $found = $this->userRepo->findOneBy(['email' => $user->getEmail()]);
        if ($found && $found !== $user) {
            throw new EmailAlreadyRegisteredException();
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
    }
}