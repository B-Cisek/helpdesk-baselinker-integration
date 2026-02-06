<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Marketplace;

class AllegroMarketplace extends AbstractMarketplace
{
    public function getSourceName(): string
    {
        return 'allegro'; // it can be replaced with enum
    }
}
