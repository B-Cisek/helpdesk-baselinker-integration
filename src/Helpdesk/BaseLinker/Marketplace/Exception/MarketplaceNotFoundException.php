<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace\Exception;

class MarketplaceNotFoundException extends \Exception
{
    public function __construct(string $sourceName)
    {
        parent::__construct(sprintf('Marketplace "%s" not found.', $sourceName));
    }
}
