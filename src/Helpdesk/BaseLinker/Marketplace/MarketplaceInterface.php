<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace;

use App\Helpdesk\BaseLinker\Marketplace\DTO\OrderCollection;

interface MarketplaceInterface
{
    /** Unique name of the marketplace. */
    public function getSourceName(): string;

    public function fetchOrders(): OrderCollection;
}
