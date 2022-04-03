<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFactory
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function create(
        array $data,
        int $statusCode = 200,
        array $headers = []
    ): Response {
        return new Response(
            $this->serializer->serialize(
                [
                    'ok' => $statusCode === 200,
                    'result' => $data,
                ],
                JsonEncoder::FORMAT
            ),
            $statusCode,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }
}
