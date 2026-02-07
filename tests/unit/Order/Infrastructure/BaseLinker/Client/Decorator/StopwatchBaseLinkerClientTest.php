<?php

declare(strict_types=1);

namespace App\Tests\Unit\Order\Infrastructure\BaseLinker\Client\Decorator;

use App\Order\Infrastructure\BaseLinker\Client\BaseLinkerClientInterface;
use App\Order\Infrastructure\BaseLinker\Client\Decorator\StopwatchBaseLinkerClient;
use App\Order\Infrastructure\BaseLinker\Client\Exception\TransportException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

final class StopwatchBaseLinkerClientTest extends TestCase
{
    #[Test]
    public function it_delegates_to_inner_client(): void
    {
        $expectedResult = ['status' => 'SUCCESS', 'orders' => []];

        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willReturn($expectedResult);

        $client = new StopwatchBaseLinkerClient(
            $inner,
            new Stopwatch(),
            $this->createStub(LoggerInterface::class),
        );

        $result = $client->request('getOrders');

        self::assertSame($expectedResult, $result);
    }

    #[Test]
    public function it_logs_performance_metrics(): void
    {
        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willReturn(['status' => 'SUCCESS']);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('info')
            ->with(
                'BaseLinker API call: {method} took {duration}ms',
                self::callback(
                    static fn(array $context): bool
                    => $context['method'] === 'getOrders'
                    && isset($context['duration'])
                    && isset($context['memory'])
                ),
            );

        $client = new StopwatchBaseLinkerClient(
            $inner,
            new Stopwatch(),
            $logger,
        );

        $client->request('getOrders');
    }

    #[Test]
    public function it_logs_metrics_even_on_exception(): void
    {
        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willThrowException(
            TransportException::fromThrowable(new \RuntimeException('Connection failed')),
        );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $client = new StopwatchBaseLinkerClient(
            $inner,
            new Stopwatch(),
            $logger,
        );

        $this->expectException(TransportException::class);
        $client->request('getOrders');
    }
}
