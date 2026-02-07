<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Data\Order;
use App\Order\Data\OrderCollection;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var array<int, Order> */
    private array $orders = [];

    public function save(Order $order): void
    {
        $this->orders[$order->orderId] = $order;
    }

    public function saveAll(OrderCollection $orders): void
    {
        foreach ($orders as $order) {
            $this->save($order);
        }
    }

    /** @return array<int, Order> */
    public function getOrders(): array
    {
        return $this->orders;
    }
}
