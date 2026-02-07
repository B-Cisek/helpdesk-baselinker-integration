<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

readonly class AmazonMarketplace extends AbstractMarketplace
{
    private const string SOURCE_NAME = 'amazon';

    public function getSourceName(): string
    {
        return self::SOURCE_NAME;
    }
}
