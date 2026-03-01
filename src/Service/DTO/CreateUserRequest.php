<?php

declare(strict_types=1);

namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 8)]
        private readonly string $login,

        #[Assert\NotBlank]
        #[Assert\Range(max: 99999999)]
        #[Assert\Type('integer')]
        private readonly int $phone,

        #[Assert\NotBlank]
        #[Assert\Length(max: 8)]
        private readonly string $password,
    ) {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
