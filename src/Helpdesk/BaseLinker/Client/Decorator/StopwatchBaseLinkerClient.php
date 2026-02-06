<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Client\Decorator;

use App\Helpdesk\BaseLinker\Client\BaseLinkerClientInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[WithMonologChannel(channel: 'baselinker_performance')]
readonly class StopwatchBaseLinkerClient implements BaseLinkerClientInterface
{
    private const string TAG = 'baselinker_api_request';

    public function __construct(
        private BaseLinkerClientInterface $inner,
        private Stopwatch $stopwatch,
        private LoggerInterface $logger,
    ) {}

    /**
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    public function request(string $method, array $parameters = []): array
    {
        $this->stopwatch->start(self::TAG);

        try {
            return $this->inner->request($method, $parameters);
        } finally {
            $event = $this->stopwatch->stop(self::TAG);
            $this->logger->info('BaseLinker API call: {method} took {duration}ms', [
                'method' => $method,
                'duration' => $event->getDuration(),
                'memory' => $event->getMemory(),
            ]);
        }
    }
}
