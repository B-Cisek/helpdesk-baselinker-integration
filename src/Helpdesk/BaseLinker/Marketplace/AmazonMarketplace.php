<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace;

class AmazonMarketplace extends AbstractMarketplace
{
    public function getSourceName(): string
    {
        return 'amazon'; // it can be replaced with enum
    }
}
