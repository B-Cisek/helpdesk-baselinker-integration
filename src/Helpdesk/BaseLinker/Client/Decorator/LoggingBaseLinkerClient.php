<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Client\Decorator;

use App\Helpdesk\BaseLinker\Client\BaseLinkerClientInterface;
use App\Helpdesk\BaseLinker\Client\Exception\BaselinkerException;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;

#[WithMonologChannel(channel: 'baselinker_api')]
readonly class LoggingBaseLinkerClient implements BaseLinkerClientInterface
{
    public function __construct(
        private BaseLinkerClientInterface $inner,
        private LoggerInterface $logger,
    ) {}

    /**
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    public function request(string $method, array $parameters = []): array
    {
        try {
            return $this->inner->request($method, $parameters);
        } catch (BaselinkerException $e) {
            $this->logger->error('BaseLinker API error: {method}', [
                'method' => $method,
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
