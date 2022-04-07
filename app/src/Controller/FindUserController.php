<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\JsonResponseFactory;
use App\Domains\Vault\Application\FindUserUseCase;
use App\Domains\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Doctrine\DBAL\Connection;

class FindUserController extends AbstractController
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    public function __invoke(Connection $connection, Request $request, string $uuid): Response
    {
        try {
            $userRepository = new MariaDbUserRepository($connection);
            $findUser = new FindUserUseCase($userRepository);
            $user = $findUser($uuid);

            return $this->jsonResponseFactory->create([
                'user' => $user->toArray()
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
