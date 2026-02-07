<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class InvoiceAddress implements \JsonSerializable
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

    /** @return array<string, string> */
    public function jsonSerialize(): array
    {
        return [
            'invoice_fullname' => $this->fullName,
            'invoice_company' => $this->company,
            'invoice_nip' => $this->nip,
            'invoice_address' => $this->address,
            'invoice_city' => $this->city,
            'invoice_state' => $this->state,
            'invoice_postcode' => $this->postcode,
            'invoice_country_code' => $this->countryCode,
            'invoice_country' => $this->country,
        ];
    }
}
