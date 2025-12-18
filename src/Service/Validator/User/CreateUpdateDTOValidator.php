<?php

namespace App\Service\Validator\User;

use App\DTO\ErrorDTO;
use App\DTO\User\CreateDTO;
use App\DTO\User\UpdateDTO;
use App\Repository\UserRepository;

class CreateUpdateDTOValidator
{
    public function __construct(
        private UserRepository $userRepo,
    ) {}
    
    public function validate(CreateDTO|UpdateDTO $dto): array
    {
        $errors = [];

        if ($dto) {
            $user = $this->userRepo->findOneBy(['email' => $dto->email]);

            if ($user) {
                $errors[] = new ErrorDTO(
                    field: "email",
                    message: 'Email ' . $dto->email . ' already registered.'
                );
            }
        }

        return $errors;
    }
}