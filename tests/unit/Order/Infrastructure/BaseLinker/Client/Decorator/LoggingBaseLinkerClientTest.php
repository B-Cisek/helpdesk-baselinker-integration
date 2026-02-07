<?php

declare(strict_types=1);

namespace App\Tests\Unit\Order\Infrastructure\BaseLinker\Client\Decorator;

use App\Order\Infrastructure\BaseLinker\Client\BaseLinkerClientInterface;
use App\Order\Infrastructure\BaseLinker\Client\Decorator\LoggingBaseLinkerClient;
use App\Order\Infrastructure\BaseLinker\Client\Exception\ApiException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggingBaseLinkerClientTest extends TestCase
{
    #[Test]
    public function it_delegates_to_inner_client(): void
    {
        $expectedResult = ['status' => 'SUCCESS', 'orders' => []];

        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willReturn($expectedResult);

        $logger = $this->createStub(LoggerInterface::class);

        $client = new LoggingBaseLinkerClient($inner, $logger);
        $result = $client->request('getOrders', ['date_from' => 123]);

        self::assertSame($expectedResult, $result);
    }

    #[Test]
    public function it_logs_error_on_baselinker_exception(): void
    {
        $exception = ApiException::fromResponse([
            'error_code' => 'ERROR_BAD_TOKEN',
            'error_message' => 'Invalid token',
        ]);

        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willThrowException($exception);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(
                'BaseLinker API error: {method}',
                self::callback(
                    static fn(array $context): bool
                    => $context['method'] === 'getOrders' && $context['exception'] === $exception
                ),
            );

        $client = new LoggingBaseLinkerClient($inner, $logger);

        $this->expectException(ApiException::class);
        $client->request('getOrders');
    }

    #[Test]
    public function it_rethrows_exception_after_logging(): void
    {
        $exception = ApiException::fromResponse([
            'error_code' => 'ERROR_UNKNOWN',
            'error_message' => 'Unknown error',
        ]);

        $inner = $this->createStub(BaseLinkerClientInterface::class);
        $inner->method('request')->willThrowException($exception);

        $logger = $this->createStub(LoggerInterface::class);

        $client = new LoggingBaseLinkerClient($inner, $logger);

        $this->expectException(ApiException::class);
        $client->request('getOrders');
    }
}
