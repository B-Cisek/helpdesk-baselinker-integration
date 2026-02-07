<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

use App\Order\Data\GetOrdersFilter;
use App\Order\Data\OrderCollection;
use App\Order\Infrastructure\BaseLinker\Client\BaseLinkerClientInterface;
use App\Order\Infrastructure\Marketplace\Mapper\OrderMapper;

abstract readonly class AbstractMarketplace implements MarketplaceInterface
{
    public function __construct(
        private BaseLinkerClientInterface $client,
        private OrderMapper $orderMapper,
    ) {}

    public function fetchOrders(GetOrdersFilter $getOrdersFilter = new GetOrdersFilter()): OrderCollection
    {
        $filter = clone $getOrdersFilter;
        $filter->filterOrderSource = $this->getSourceName();

        $response = $this->client->request('getOrders', $filter->toArray());

        return $this->orderMapper->mapFromResponse($response);
    }
}
