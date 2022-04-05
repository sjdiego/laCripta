<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\JsonResponseFactory;
use App\Vault\Application\CreateUserUseCase;
use App\Vault\Infrastructure\Hashing\PasswordDefaultHashing;
use App\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Connection;

class CreateUserController extends AbstractController
{
    public function __construct(private JsonResponseFactory $jsonResponseFactory)
    {
    }

    public function __invoke(Connection $connection, Request $request): Response
    {
        $createUser = new CreateUserUseCase(
            new MariaDbUserRepository($connection),
            new PasswordDefaultHashing()
        );

        try {
            $user = $createUser(
                Uuid::v4()->toRfc4122(),
                $request->request->get('name'),
                $request->request->get('email'),
                $request->request->get('password')
            );

            return $this->jsonResponseFactory->create(['user' => $user->toArray()]);
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
