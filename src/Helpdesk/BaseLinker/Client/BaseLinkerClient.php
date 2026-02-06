<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Client;

use App\Helpdesk\BaseLinker\Exception\ApiException;
use App\Helpdesk\BaseLinker\Exception\RateLimitException;
use App\Helpdesk\BaseLinker\Exception\TransportException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class BaseLinkerClient implements BaseLinkerClientInterface
{
    private const string API_URL = 'https://api.baselinker.com/connector.php';

    public function __construct(
        #[Autowire(param: 'baselinker.api_token')]
        private string $apiToken,
        private HttpClientInterface $httpClient,
    ) {}

    public function request(string $method, array $parameters = []): array
    {
        try {
            $response = $this->httpClient->request(Request::METHOD_POST, self::API_URL, [
                'headers' => [
                    'X-BLToken' => $this->apiToken,
                ],
                'body' => [
                    'method' => $method,
                    'parameters' => json_encode($parameters, \JSON_THROW_ON_ERROR),
                ],
            ]);

            if ($response->getStatusCode() === 429) {
                throw RateLimitException::create();
            }

            /** @var array<string, mixed> $data */
            $data = $response->toArray();

            if (($data['status'] ?? '') !== 'SUCCESS') {
                throw ApiException::fromResponse($data);
            }

            return $data;
        } catch (RateLimitException|ApiException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw TransportException::fromThrowable($e);
        }
    }
}
