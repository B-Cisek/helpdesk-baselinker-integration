<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace\Mapper;

use App\Order\Data\Order;
use App\Order\Data\OrderCollection;

class OrderMapper
{
    /** @param array<string, mixed> $response */
    public function mapFromResponse(array $response): OrderCollection
    {
        return new OrderCollection(
            array_map(Order::fromArray(...), $response['orders']),
        );
    }
}
