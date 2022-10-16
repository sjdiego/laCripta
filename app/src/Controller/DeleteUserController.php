<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domains\Vault\Application\DeleteUserUseCase;
use App\Factory\JsonResponseFactory;
use App\Domains\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Doctrine\DBAL\Connection;

class DeleteUserController extends AbstractController
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    public function __invoke(Connection $connection, Request $request, string $uuid): Response
    {
        try {
            $userRepository = new MariaDbUserRepository($connection);
            $deleteUser = new DeleteUserUseCase($userRepository);
            $deleteUser($uuid);

            return $this->jsonResponseFactory->create([
                'message' => 'User has been deleted successfully',
                'uuid' => $uuid,
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponseFactory->create(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
