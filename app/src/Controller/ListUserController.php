<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\JsonResponseFactory;
use App\Vault\Application\ListUserUseCase;
use App\Vault\Domain\User;
use App\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ListUserController extends AbstractController
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    public function __invoke(Connection $connection): Response
    {
        try {
            $userRepository = new MariaDbUserRepository($connection);
            $listUsers = new ListUserUseCase($userRepository);

            return $this->jsonResponseFactory->create([
                'users' => array_map(
                    fn (User $user) => $user->toArray(),
                    $listUsers()
                ),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponseFactory->create(
                [
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
