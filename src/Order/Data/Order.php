<?php

declare(strict_types=1);

namespace App\Order\Data;

readonly class Order
{
    /**
     * @param array<OrderProduct> $products
     */
    public function __construct(
        public int $orderId,
        public int $shopOrderId,
        public string $externalOrderId,
        public string $orderSource,
        public int $orderSourceId,
        public string $orderSourceInfo,
        public int $orderStatusId,
        public bool $confirmed,
        public int $dateConfirmed,
        public int $dateAdd,
        public int $dateInStatus,
        public string $userLogin,
        public string $phone,
        public string $email,
        public string $userComments,
        public string $adminComments,
        public string $currency,
        public string $paymentMethod,
        public string $paymentMethodCod,
        public float $paymentDone,
        public int $deliveryMethodId,
        public string $deliveryMethod,
        public float $deliveryPrice,
        public string $deliveryPackageModule,
        public string $deliveryPackageNr,
        public DeliveryAddress $deliveryAddress,
        public DeliveryPoint $deliveryPoint,
        public InvoiceAddress $invoiceAddress,
        public string $wantInvoice,
        public string $extraField1,
        public string $extraField2,
        public string $orderPage,
        public int $pickState,
        public int $packState,
        public array $products,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            orderId: (int) $data['order_id'],
            shopOrderId: (int) $data['shop_order_id'],
            externalOrderId: (string) $data['external_order_id'],
            orderSource: (string) $data['order_source'],
            orderSourceId: (int) $data['order_source_id'],
            orderSourceInfo: (string) $data['order_source_info'],
            orderStatusId: (int) $data['order_status_id'],
            confirmed: (bool) $data['confirmed'],
            dateConfirmed: (int) $data['date_confirmed'],
            dateAdd: (int) $data['date_add'],
            dateInStatus: (int) $data['date_in_status'],
            userLogin: (string) $data['user_login'],
            phone: (string) $data['phone'],
            email: (string) $data['email'],
            userComments: (string) $data['user_comments'],
            adminComments: (string) $data['admin_comments'],
            currency: (string) $data['currency'],
            paymentMethod: (string) $data['payment_method'],
            paymentMethodCod: (string) $data['payment_method_cod'],
            paymentDone: (float) $data['payment_done'],
            deliveryMethodId: (int) $data['delivery_method_id'],
            deliveryMethod: (string) $data['delivery_method'],
            deliveryPrice: (float) $data['delivery_price'],
            deliveryPackageModule: (string) $data['delivery_package_module'],
            deliveryPackageNr: (string) $data['delivery_package_nr'],
            deliveryAddress: DeliveryAddress::fromArray($data),
            deliveryPoint: DeliveryPoint::fromArray($data),
            invoiceAddress: InvoiceAddress::fromArray($data),
            wantInvoice: (string) $data['want_invoice'],
            extraField1: (string) $data['extra_field_1'],
            extraField2: (string) $data['extra_field_2'],
            orderPage: (string) $data['order_page'],
            pickState: (int) $data['pick_state'],
            packState: (int) $data['pack_state'],
            products: array_map(OrderProduct::fromArray(...), $data['products']),
        );
    }
}
