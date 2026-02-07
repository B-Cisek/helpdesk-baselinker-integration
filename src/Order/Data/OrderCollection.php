<?php

declare(strict_types=1);

namespace App\Order\Data;

use Traversable;

/**
 * @implements \IteratorAggregate<int, Order>
 */
final readonly class OrderCollection implements \IteratorAggregate, \Countable
{
    /**
     * @param list<Order> $orders
     */
    public function __construct(
        private array $orders
    ) {}

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->orders);
    }

    public function count(): int
    {
        return count($this->orders);
    }

    /**
     * @return list<Order>
     */
    public function toArray(): array
    {
        return $this->orders;
    }
}
