<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class OrderProduct implements \JsonSerializable
{
    public function __construct(
        public string $storage,
        public int $storageId,
        public int $orderProductId,
        public string $productId,
        public string $variantId,
        public string $name,
        public string $attributes,
        public string $sku,
        public string $ean,
        public string $location,
        public int $warehouseId,
        public string $auctionId,
        public float $priceBrutto,
        public int $taxRate,
        public int $quantity,
        public float $weight,
        public int $bundleId,
    ) {}

    /** @param array<string, string|int|float> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            storage: $data['storage'],
            storageId: $data['storage_id'],
            orderProductId: $data['order_product_id'],
            productId: (string) $data['product_id'],
            variantId: (string) $data['variant_id'],
            name: $data['name'],
            attributes: $data['attributes'],
            sku: $data['sku'],
            ean: $data['ean'],
            location: $data['location'],
            warehouseId: $data['warehouse_id'],
            auctionId: (string) $data['auction_id'],
            priceBrutto: (float) $data['price_brutto'],
            taxRate: $data['tax_rate'],
            quantity: $data['quantity'],
            weight: (float) $data['weight'],
            bundleId: $data['bundle_id'],
        );
    }

    /** @return array<string, string|int|float> */
    public function jsonSerialize(): array
    {
        return [
            'storage' => $this->storage,
            'storage_id' => $this->storageId,
            'order_product_id' => $this->orderProductId,
            'product_id' => $this->productId,
            'variant_id' => $this->variantId,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'sku' => $this->sku,
            'ean' => $this->ean,
            'location' => $this->location,
            'warehouse_id' => $this->warehouseId,
            'auction_id' => $this->auctionId,
            'price_brutto' => $this->priceBrutto,
            'tax_rate' => $this->taxRate,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'bundle_id' => $this->bundleId,
        ];
    }
}
