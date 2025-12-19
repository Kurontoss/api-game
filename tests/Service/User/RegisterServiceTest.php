<?php

namespace App\Tests\Service\User;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Service\User\RegisterService;

class RegisterServiceTest extends TestCase
{
    private RegisterService $registerService;
    private MockObject $passwordHasherMock;

    protected function setUp(): void
    {
        $this->passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $this->service = new RegisterService(
            $this->passwordHasherMock,
        );
    }

    public function testRegisterHashesPasswordAndSetsRole(): void
    {
        // Given
        $user = new User();
        $user->setPassword('plain-password');

        $this->passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'plain-password')
            ->willReturn('hashed-password');

        // When
        $this->service->register($user);

        // Then
        $this->assertEquals('hashed-password', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testUpdateHashesPasswordButDoesNotChangeRoles(): void
    {
        // Given
        $user = new User();
        $user->setPassword('old-password');
        $user->setRoles(['ROLE_ADMIN']);

        $this->passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'old-password')
            ->willReturn('new-hashed-password');

        // When
        $this->service->update($user);

        // Then
        $this->assertEquals('new-hashed-password', $user->getPassword());
        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
    }
}