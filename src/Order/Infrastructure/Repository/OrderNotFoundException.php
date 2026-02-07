<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

final class OrderNotFoundException extends \RuntimeException
{
    public static function withId(int $orderId): self
    {
        return new self(sprintf('Order with ID %d not found', $orderId));
    }
}
