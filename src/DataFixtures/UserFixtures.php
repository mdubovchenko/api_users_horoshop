<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setLogin("user_{$i}");
            $user->setPhone(random_int(11111111, 99999999));
            $role = match ($i) {
                1 => 'ROLE_TEST',
                2 => 'ROLE_ROOT',
                default => 'ROLE_USER',
            };
            $user->setRoles([$role]);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                '123456'
            );

            $user->setPassword($hashedPassword);
            $user->setApiToken(bin2hex(random_bytes(32)));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
