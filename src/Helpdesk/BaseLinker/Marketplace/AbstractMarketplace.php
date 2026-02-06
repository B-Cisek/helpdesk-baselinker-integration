<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace;

use App\Helpdesk\BaseLinker\Client\BaseLinkerClientInterface;
use App\Helpdesk\BaseLinker\Marketplace\DTO\Order;
use App\Helpdesk\BaseLinker\Marketplace\DTO\OrderCollection;

abstract class AbstractMarketplace implements MarketplaceInterface
{
    public function __construct(private readonly BaseLinkerClientInterface $client) {}

    public function fetchOrders(): OrderCollection
    {
        $data = $this->client->request('getOrders', [
            'filter_order_source' => $this->getSourceName(),
        ]);

        return new OrderCollection(array_map(fn(array $order) => Order::fromArray($order), $data['orders']));
    }
}
