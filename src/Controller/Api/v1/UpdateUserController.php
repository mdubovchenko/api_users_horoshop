<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Repository\UserRepository;
use App\Service\DTO\UpdateUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    '/v1/api/users',
    name: 'api_v1_users_update',
    methods: ['PUT']
)]
#[IsGranted('ROLE_USER')]
class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(#[MapRequestPayload] UpdateUserRequest $request): JsonResponse {
        $user = $this->userRepository->find($request->getId());

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        try {
            $user->setLogin($request->getLogin());
            $user->setPhone($request->getPhone());

            if ($request->getPassword()) {
                $hashedPassword = $this->hasher->hashPassword($user, $request->getPassword());
                $user->setPassword($hashedPassword);
            }

            $this->em->flush();
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], 500);
        }

        return $this->json([
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'status' => 'updated'
        ]);
    }
}
