<?php

declare(strict_types=1);

namespace App\Order\Data;

class GetOrdersFilter
{
    public function __construct(
        public ?int $orderId = null,
        public ?int $dateConfirmedFrom = null,
        public ?int $dateFrom = null,
        public ?int $idFrom = null,
        public bool $getUnconfirmedOrders = false,
        public ?int $statusId = null,
        public ?string $filterEmail = null,
        public ?string $filterOrderSource = null,
        public ?int $filterOrderSourceId = null,
        public ?int $filterShopOrderId = null,
        public bool $includeCustomExtraFields = false,
        public bool $includeCommissionData = false,
        public bool $includeConnectData = false,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'order_id' => $this->orderId,
            'date_confirmed_from' => $this->dateConfirmedFrom,
            'date_from' => $this->dateFrom,
            'id_from' => $this->idFrom,
            'get_unconfirmed_orders' => $this->getUnconfirmedOrders,
            'status_id' => $this->statusId,
            'filter_email' => $this->filterEmail,
            'filter_order_source' => $this->filterOrderSource,
            'filter_order_source_id' => $this->filterOrderSourceId,
            'filter_shop_order_id' => $this->filterShopOrderId,
            'include_custom_extra_fields' => $this->includeCustomExtraFields,
            'include_commission_data' => $this->includeCommissionData,
            'include_connect_data' => $this->includeConnectData,
        ], static fn(mixed $value): bool => $value !== null);
    }
}
