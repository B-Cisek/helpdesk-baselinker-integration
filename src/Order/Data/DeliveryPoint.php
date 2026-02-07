<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class DeliveryPoint implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $address,
        public string $postcode,
        public string $city,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['delivery_point_id'] ?? ''),
            name: (string) ($data['delivery_point_name'] ?? ''),
            address: (string) ($data['delivery_point_address'] ?? ''),
            postcode: (string) ($data['delivery_point_postcode'] ?? ''),
            city: (string) ($data['delivery_point_city'] ?? ''),
        );
    }

    /** @return array<string, string> */
    public function jsonSerialize(): array
    {
        return [
            'delivery_point_id' => $this->id,
            'delivery_point_name' => $this->name,
            'delivery_point_address' => $this->address,
            'delivery_point_postcode' => $this->postcode,
            'delivery_point_city' => $this->city,
        ];
    }
}
