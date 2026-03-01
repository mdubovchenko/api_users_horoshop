<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    '/v1/api/users/{id}',
    name: 'api_v1_delete_user',
    requirements: ['id' => '\d+'],
    methods: ['DELETE']
)]
#[IsGranted('ROLE_ROOT')]
class DeleteUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], 500);
        }


        return $this->json(['status' => 'deleted']);
    }
}
