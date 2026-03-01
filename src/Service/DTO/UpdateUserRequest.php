<?php

declare(strict_types=1);

namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        private readonly int $id,

        #[Assert\NotBlank]
        #[Assert\Length(max: 8)]
        private readonly string $login,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        private readonly int $phone,

        #[Assert\Length(max: 8)]
        private readonly ?string $password = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
