<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

use App\Order\Data\GetOrdersFilter;
use App\Order\Data\OrderCollection;

interface MarketplaceInterface
{
    public function getSourceName(): string;

    public function fetchOrders(GetOrdersFilter $getOrdersFilter = new GetOrdersFilter()): OrderCollection;
}
