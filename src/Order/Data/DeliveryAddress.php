<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class DeliveryAddress
{
    public function __construct(
        public string $fullName,
        public string $company,
        public string $address,
        public string $city,
        public string $state,
        public string $postcode,
        public string $countryCode,
        public string $country,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            fullName: (string) ($data['delivery_fullname'] ?? ''),
            company: (string) ($data['delivery_company'] ?? ''),
            address: (string) ($data['delivery_address'] ?? ''),
            city: (string) ($data['delivery_city'] ?? ''),
            state: (string) ($data['delivery_state'] ?? ''),
            postcode: (string) ($data['delivery_postcode'] ?? ''),
            countryCode: (string) ($data['delivery_country_code'] ?? ''),
            country: (string) ($data['delivery_country'] ?? ''),
        );
    }
}
