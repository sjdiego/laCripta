<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\JsonResponseFactory;
use App\Domains\Vault\Application\UpdateUserUseCase;
use App\Domains\Vault\Infrastructure\Hashing\PasswordDefaultHashing;
use App\Domains\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Doctrine\DBAL\Connection;

class UpdateUserController extends AbstractController
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    public function __invoke(Connection $connection, Request $request, string $uuid): Response
    {
        $updateUser = new UpdateUserUseCase(
            new MariaDbUserRepository($connection),
            new PasswordDefaultHashing()
        );

        try {
            $content = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid parameters", 1);
            }

            $user = $updateUser($uuid, $content);

            return $this->jsonResponseFactory->create([
                'user' => $user->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponseFactory->create(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
