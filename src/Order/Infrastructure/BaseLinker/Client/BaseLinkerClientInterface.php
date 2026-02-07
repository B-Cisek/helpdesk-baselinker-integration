<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\BaseLinker\Client;

interface BaseLinkerClientInterface
{
    /**
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    public function request(string $method, array $parameters = []): array;
}
