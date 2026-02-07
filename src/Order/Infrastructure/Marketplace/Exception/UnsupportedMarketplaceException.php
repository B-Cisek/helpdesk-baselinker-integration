<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace\Exception;

final class UnsupportedMarketplaceException extends \RuntimeException
{
    public function __construct(string $sourceName)
    {
        parent::__construct(sprintf('Marketplace with given source name "%s" is not supported.', $sourceName));
    }
}
