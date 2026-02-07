<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class InvoiceAddress
{
    public function __construct(
        public string $fullName,
        public string $company,
        public string $nip,
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
            fullName: (string) ($data['invoice_fullname'] ?? ''),
            company: (string) ($data['invoice_company'] ?? ''),
            nip: (string) ($data['invoice_nip'] ?? ''),
            address: (string) ($data['invoice_address'] ?? ''),
            city: (string) ($data['invoice_city'] ?? ''),
            state: (string) ($data['invoice_state'] ?? ''),
            postcode: (string) ($data['invoice_postcode'] ?? ''),
            countryCode: (string) ($data['invoice_country_code'] ?? ''),
            country: (string) ($data['invoice_country'] ?? ''),
        );
    }
}
