<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Data\Order;
use App\Order\Data\OrderCollection;

interface OrderRepositoryInterface
{
    public function save(Order $order): void;

    public function saveAll(OrderCollection $orders): void;
}
