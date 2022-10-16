<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domains\Vault\Application\CheckUserPasswordUseCase;
use App\Domains\Vault\Infrastructure\Hashing\PasswordDefaultHashing;
use App\Domains\Vault\Infrastructure\Repositories\MariaDbUserRepository;
use App\Factory\JsonResponseFactory;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

class CheckUserPasswordController extends AbstractController
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    public function __invoke(Connection $connection, Request $request, string $uuid): Response
    {
        try {
            $checkUser = new CheckUserPasswordUseCase(
                new MariaDbUserRepository($connection),
                new PasswordDefaultHashing()
            );

            return $this->jsonResponseFactory->create([
                'user' => sprintf(
                    'Provided password for %s %s valid.',
                    $uuid,
                    $checkUser($uuid, $request->get('password')) ? 'is' : 'is NOT'
                )
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
