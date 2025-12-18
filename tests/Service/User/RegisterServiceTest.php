<?php

namespace App\Tests\Service\User;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;

class RegisterServiceTest extends TestCase
{
    public function testRegisterSetsHashedPasswordAndRole(): void
    {
        $passwordHasherStub = $this->createStub(UserPasswordHasherInterface::class);
        $passwordHasherStub
            ->method('hashPassword')
            ->willReturn('hashed_password');
        
        $userRepoStub = $this->createStub(UserRepository::class);

        $service = new RegisterService($passwordHasherStub, $userRepoStub);

        $user = new User();
        $user->setPassword('password');

        $service->register($user);

        $this->assertEquals('hashed_password', $user->getPassword(), 'Password should be hashed');
        $this->assertContains('ROLE_USER', $user->getRoles(), 'User should have ROLE_USER');
    }

    public function testUpdateSetsHashedPassword(): void
    {
        $passwordHasherStub = $this->createStub(UserPasswordHasherInterface::class);
        $passwordHasherStub
            ->method('hashPassword')
            ->willReturn('hashed_password');
        
        $userRepoStub = $this->createStub(UserRepository::class);

        $service = new RegisterService($passwordHasherStub, $userRepoStub);

        $user = new User();
        $user->setPassword('password');

        $service->update($user);

        $this->assertEquals('hashed_password', $user->getPassword(), 'Password should be hashed');
    }
}