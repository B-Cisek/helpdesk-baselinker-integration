<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace;

use App\Helpdesk\BaseLinker\Marketplace\Exception\MarketplaceNotFoundException;

final class MarketplaceProvider
{
    /** @var array<string, MarketplaceInterface>  */
    private array $strategyMap = [];

    /** @param array<string, MarketplaceInterface> $strategies */
    public function __construct(iterable $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->strategyMap[$strategy->getSourceName()] = $strategy;
        }
    }

    /**
     * @throws MarketplaceNotFoundException
     */
    public function get(string $sourceName): MarketplaceInterface
    {
        if (!isset($this->strategyMap[$sourceName])) {
            throw new MarketplaceNotFoundException($sourceName);
        }

        return $this->strategyMap[$sourceName];
    }

    /** @return list<string> */
    public function getAvailableSources(): array
    {
        return array_keys($this->strategyMap);
    }
}
