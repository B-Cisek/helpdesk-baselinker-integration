<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

use App\Order\Infrastructure\Marketplace\Exception\UnsupportedMarketplaceException;

final readonly class MarketplaceProvider
{
    /** @var array<string, MarketplaceInterface>  */
    private array $strategyMap;

    /** @param array<string, MarketplaceInterface> $strategies */
    public function __construct(iterable $strategies)
    {
        $strategyMap = [];
        foreach ($strategies as $strategy) {
            $strategyMap[$strategy->getSourceName()] = $strategy;
        }
        $this->strategyMap = $strategyMap;
    }

    /**
     * @throws UnsupportedMarketplaceException
     */
    public function get(string $sourceName): MarketplaceInterface
    {
        if (!isset($this->strategyMap[$sourceName])) {
            throw new UnsupportedMarketplaceException($sourceName);
        }

        return $this->strategyMap[$sourceName];
    }

    /** @return list<string> */
    public function getAvailableSources(): array
    {
        return array_keys($this->strategyMap);
    }
}
