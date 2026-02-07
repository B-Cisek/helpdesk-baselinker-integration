<?php

declare(strict_types=1);

namespace App\Tests\Unit\Order\Infrastructure\BaseLinker\Client;

use App\Order\Infrastructure\BaseLinker\Client\BaseLinkerClient;
use App\Order\Infrastructure\BaseLinker\Client\Exception\ApiException;
use App\Order\Infrastructure\BaseLinker\Client\Exception\RateLimitException;
use App\Order\Infrastructure\BaseLinker\Client\Exception\TransportException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class BaseLinkerClientTest extends TestCase
{
    private const string API_TOKEN = 'test-token-123';

    #[Test]
    public function it_successful_request_returns_orders(): void
    {
        $httpClient = new MockHttpClient(new MockResponse(
            '{"status":"SUCCESS","orders":[]}'
        ));

        $client = new BaseLinkerClient(self::API_TOKEN, $httpClient);

        $result = $client->request('getOrders', ['date_from' => 1234567890]);

        self::assertSame('SUCCESS', $result['status']);
        self::assertSame([], $result['orders']);
    }

    #[Test]
    public function it_sends_correct_headers_and_body(): void
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) {
            self::assertSame('POST', $method);
            self::assertSame('https://api.baselinker.com/connector.php', $url);
            self::assertContains('X-BLToken: test-token-123', $options['headers']);

            $body = [];
            parse_str($options['body'], $body);
            self::assertSame('getOrders', $body['method']);
            self::assertSame('{"date_from":1234567890}', $body['parameters']);

            return new MockResponse('{"status":"SUCCESS","orders":[]}');
        });

        $client = new BaseLinkerClient(self::API_TOKEN, $httpClient);
        $client->request('getOrders', ['date_from' => 1234567890]);
    }

    #[Test]
    public function it_throws_api_exception_on_parse_json_parameters_error(): void
    {
        $httpClient = new MockHttpClient(new MockResponse(
            '{"status":"ERROR","error_code":"ERROR_PARSE_JSON_PARAMETERS","error_message":""}'
        ));
        $client = new BaseLinkerClient(self::API_TOKEN, $httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('ERROR_PARSE_JSON_PARAMETERS');

        $client->request('getOrders');
    }

    #[Test]
    public function it_throws_api_exception_on_bad_token(): void
    {
        $httpClient = new MockHttpClient(new MockResponse(
            '{"status":"ERROR","error_code":"ERROR_BAD_TOKEN","error_message":"Invalid user token"}'
        ));
        $client = new BaseLinkerClient('invalid-token', $httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid user token');

        $client->request('getOrders');
    }

    #[Test]
    public function it_throws_rate_limit_exception_on_429(): void
    {
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => 429]));
        $client = new BaseLinkerClient(self::API_TOKEN, $httpClient);

        $this->expectException(RateLimitException::class);

        $client->request('getOrders');
    }

    #[Test]
    public function it_throws_transport_exception_on_network_error(): void
    {
        $httpClient = new MockHttpClient(new MockResponse([
            new \RuntimeException('Connection refused'),
        ]));
        $client = new BaseLinkerClient(self::API_TOKEN, $httpClient);

        $this->expectException(TransportException::class);

        $client->request('getOrders');
    }
}
