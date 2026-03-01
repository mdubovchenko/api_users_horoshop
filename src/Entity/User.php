<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
#[ORM\UniqueConstraint(name: "unique_login_pass", columns: ["login", "password"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    #[Assert\Length(max: 8)]
    private string $login;

    #[ORM\Column(type: 'integer')]
    private int $phone;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(unique: true)]
    private string $apiToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles ?: ['ROLE_USER'];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->apiToken;
    }

    public function eraseCredentials(): void
    {}

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $token): self
    {
        $this->apiToken = $token;

        return $this;
    }
}
