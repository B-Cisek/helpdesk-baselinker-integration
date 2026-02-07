<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

readonly class AllegroMarketplace extends AbstractMarketplace
{
    private const string SOURCE_NAME = 'allegro';

    public function getSourceName(): string
    {
        return self::SOURCE_NAME;
    }
}
