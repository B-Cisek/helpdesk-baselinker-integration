<?php

declare(strict_types=1);

namespace App\Tests\Integration\Order\Application\Command\Sync;

use App\Order\Application\Command\Sync\FetchOrders;
use App\Order\Application\Command\Sync\FetchOrdersHandler;
use App\Order\Infrastructure\BaseLinker\Client\BaseLinkerClientInterface;
use App\Order\Infrastructure\Marketplace\Exception\UnsupportedMarketplaceException;
use App\Order\Infrastructure\Repository\InMemoryOrderRepository;
use App\Order\Infrastructure\Repository\OrderRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FetchOrdersTest extends WebTestCase
{
    #[Test]
    public function it_fetches_orders_and_saves_them_to_repository(): void
    {
        $container = self::getContainer();

        /** @var BaseLinkerClientInterface&MockObject $baseLinkerClient */
        $baseLinkerClient = $this->createMock(BaseLinkerClientInterface::class);

        $container->set(BaseLinkerClientInterface::class, $baseLinkerClient);

        $ordersData = [
            'orders' => [
                [
                    'order_id' => 123,
                    'shop_order_id' => 456,
                    'external_order_id' => 'EXT123',
                    'order_source' => 'allegro',
                    'order_source_id' => 1,
                    'order_source_info' => 'Allegro source',
                    'order_status_id' => 1,
                    'confirmed' => true,
                    'date_confirmed' => 1738944000,
                    'date_add' => 1738944000,
                    'date_in_status' => 1738944000,
                    'user_login' => 'user123',
                    'phone' => '123456789',
                    'email' => 'test@example.com',
                    'user_comments' => '',
                    'admin_comments' => '',
                    'currency' => 'PLN',
                    'payment_method' => 'card',
                    'payment_method_cod' => '0',
                    'payment_done' => 100.0,
                    'delivery_method_id' => 1,
                    'delivery_method' => 'courier',
                    'delivery_price' => 15.0,
                    'delivery_package_module' => '',
                    'delivery_package_nr' => '',
                    'delivery_fullname' => 'John Doe',
                    'delivery_company' => '',
                    'delivery_address' => 'Test Street 1',
                    'delivery_city' => 'Warsaw',
                    'delivery_state' => '',
                    'delivery_postcode' => '00-001',
                    'delivery_country_code' => 'PL',
                    'delivery_country' => 'Poland',
                    'delivery_point_id' => '',
                    'delivery_point_name' => '',
                    'delivery_point_address' => '',
                    'delivery_point_postcode' => '',
                    'delivery_point_city' => '',
                    'invoice_fullname' => '',
                    'invoice_company' => '',
                    'invoice_nip' => '',
                    'invoice_address' => '',
                    'invoice_city' => '',
                    'invoice_state' => '',
                    'invoice_postcode' => '',
                    'invoice_country_code' => '',
                    'invoice_country' => '',
                    'want_invoice' => '0',
                    'extra_field_1' => '',
                    'extra_field_2' => '',
                    'order_page' => '',
                    'pick_state' => 0,
                    'pack_state' => 0,
                    'products' => [],
                ],
            ],
        ];

        $baseLinkerClient->expects($this->once())
            ->method('request')
            ->with('getOrders', $this->callback(fn($filter) => $filter['filter_order_source'] === 'allegro'))
            ->willReturn($ordersData);

        /** @var FetchOrdersHandler $handler */
        $handler = $container->get(FetchOrdersHandler::class);
        $handler(new FetchOrders('allegro'));

        /** @var InMemoryOrderRepository $repository */
        $repository = $container->get(OrderRepositoryInterface::class);
        $orders = $repository->getOrders();

        $this->assertCount(1, $orders);
        $this->assertArrayHasKey(123, $orders);
        $this->assertEquals('EXT123', $orders[123]->externalOrderId);
        $this->assertEquals('test@example.com', $orders[123]->email);
    }

    #[Test]
    public function it_throws_exception_for_unsupported_marketplace(): void
    {
        $container = self::getContainer();
        /** @var FetchOrdersHandler $handler */
        $handler = $container->get(FetchOrdersHandler::class);

        $this->expectException(UnsupportedMarketplaceException::class);
        $this->expectExceptionMessage('Marketplace with given source name "unknown" is not supported.');

        $handler(new FetchOrders('unknown'));
    }
}
