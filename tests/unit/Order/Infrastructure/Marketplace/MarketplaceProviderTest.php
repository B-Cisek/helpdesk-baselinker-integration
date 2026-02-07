<?php

declare(strict_types=1);

namespace App\Tests\Unit\Order\Infrastructure\Marketplace;

use App\Order\Infrastructure\Marketplace\Exception\UnsupportedMarketplaceException;
use App\Order\Infrastructure\Marketplace\MarketplaceInterface;
use App\Order\Infrastructure\Marketplace\MarketplaceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MarketplaceProviderTest extends TestCase
{
    #[Test]
    public function it_returns_marketplace_by_source_name(): void
    {
        $allegro = $this->createMarketplaceStub('allegro');
        $amazon = $this->createMarketplaceStub('amazon');

        $provider = new MarketplaceProvider(['allegro' => $allegro, 'amazon' => $amazon]);

        self::assertSame($allegro, $provider->get('allegro'));
        self::assertSame($amazon, $provider->get('amazon'));
    }

    #[Test]
    public function it_throws_exception_for_unsupported_marketplace(): void
    {
        $provider = new MarketplaceProvider([]);

        $this->expectException(UnsupportedMarketplaceException::class);
        $this->expectExceptionMessage('unknown');

        $provider->get('unknown');
    }

    #[Test]
    public function it_returns_available_sources(): void
    {
        $allegro = $this->createMarketplaceStub('allegro');
        $amazon = $this->createMarketplaceStub('amazon');

        $provider = new MarketplaceProvider(['allegro' => $allegro, 'amazon' => $amazon]);

        self::assertSame(['allegro', 'amazon'], $provider->getAvailableSources());
    }

    #[Test]
    public function it_returns_empty_sources_when_no_strategies(): void
    {
        $provider = new MarketplaceProvider([]);

        self::assertSame([], $provider->getAvailableSources());
    }

    private function createMarketplaceStub(string $sourceName): MarketplaceInterface
    {
        $marketplace = $this->createStub(MarketplaceInterface::class);
        $marketplace->method('getSourceName')->willReturn($sourceName);

        return $marketplace;
    }
}
