<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Sync;

use App\Order\Infrastructure\Marketplace\MarketplaceProvider;
use App\Shared\Application\Command\Sync\CommandHandler;

final readonly class FetchOrdersHandler implements CommandHandler
{
    public function __construct(private MarketplaceProvider $marketplaceProvider) {}

    public function __invoke(FetchOrders $command): void
    {
        $marketplace = $this->marketplaceProvider->get($command->marketplaceName);

        $orderCollection = $marketplace->fetchOrders();

        dd($orderCollection);
    }
}
