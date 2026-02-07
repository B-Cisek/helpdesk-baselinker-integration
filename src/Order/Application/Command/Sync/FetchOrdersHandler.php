<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Sync;

use App\Order\Infrastructure\Marketplace\MarketplaceProvider;
use App\Order\Infrastructure\Repository\OrderRepositoryInterface;
use App\Shared\Application\Command\Sync\CommandHandler;
use Psr\Log\LoggerInterface;

final readonly class FetchOrdersHandler implements CommandHandler
{
    public function __construct(
        private MarketplaceProvider $marketplaceProvider,
        private OrderRepositoryInterface $orderRepository,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(FetchOrders $command): void
    {
        $this->logger->info('Start fetching orders from marketplace', ['marketplace' => $command->marketplaceName]);

        $marketplace = $this->marketplaceProvider->get($command->marketplaceName);

        $orders = $marketplace->fetchOrders();

        $this->orderRepository->saveAll($orders);

        $this->logger->info('Finished fetching orders from marketplace', ['marketplace' => $command->marketplaceName]);
    }
}
