<?php

declare(strict_types=1);

namespace App\Order\Application\Command\Sync;

use App\Shared\Application\Command\Sync\Command;

final readonly class FetchOrders implements Command
{
    public function __construct(public string $marketplaceName) {}
}
