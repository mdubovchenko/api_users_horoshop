<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Entity\User;
use App\Service\DTO\CreateUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    '/v1/api/users',
    name: 'api_v1_users_create',
    methods: ['POST']
)]
#[IsGranted('ROLE_USER')]
class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function __invoke(#[MapRequestPayload] CreateUserRequest $request): JsonResponse {
        try {
            $user = new User();
            $user->setLogin($request->getLogin());
            $user->setPhone($request->getPhone());
            $user->setPassword($this->hasher->hashPassword($user, $request->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $user->setApiToken(bin2hex(random_bytes(32)));

            $this->em->persist($user);
            $this->em->flush();
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], 500);
        }

        return $this->json([
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'password' => $user->getPassword(),
        ]);
    }
}
