<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    '/v1/api/users/{id}',
    name: 'api_v1_get_user',
    requirements: ['id' => '\d+'],
    methods: ['GET']
)]
#[IsGranted('ROLE_USER')]
class GetUserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id'       => $user->getId(),
            'login'    => $user->getLogin(),
            'phone'    => $user->getPhone(),
            'password' => $user->getPassword(),
        ]);
    }
}
