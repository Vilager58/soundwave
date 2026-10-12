<?php
namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';

class Order{
    private int $id;
    private string $date;
    private int $statusId;
    private string $status;
    private string $trackNumber;
    private string $deliveryType;
    private string $deliveryAddress;
    private string $paymentMethod;
    private array $items;
    private float $total;
    private string $createdAt;

    public function __construct(int $id,
                                string $date,
                                int $statusId,
                                string $status,
                                string $trackNumber,
                                string $deliveryType,
                                string $deliveryAddress,
                                string $paymentMethod,
                                array $items,
                                float $total,
                                string $createdAt)
    {
        $this->id = $id;
        $this->date = $date;
        $this->statusId = $statusId;
        $this->status = $status;
        $this->trackNumber = $trackNumber;
        $this->deliveryType = $deliveryType;
        $this->deliveryAddress = $deliveryAddress;
        $this->paymentMethod = $paymentMethod;
        $this->items = $items;
        $this->total = $total;
        $this->createdAt = $createdAt;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getDate(): string{
        return $this->date;
    }

    public function getStatusId(): int{
        return $this->statusId;
    }

    public function getStatus(): string{
        return $this->status;
    }

    public function getTrackNumber(): string{
        return $this->trackNumber;
    }

    public function getDeliveryType(): string{
        return $this->deliveryType;
    }

    public function getDeliveryAddress(): string{
        return $this->deliveryAddress;
    }

    public function getPaymentMethod(): string{
        return $this->paymentMethod;
    }

    public function getItems(): array{
        return $this->items;
    }

    public function getTotal(): float{
        return $this->total;
    }

    public function getCreatedAt(): string{
        return $this->createdAt;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'date' => $this->date,
            'status' => $this->statusId,
            'statusLabel' => $this->status,
            'trackNumber' => $this->trackNumber,
            'deliveryType' => $this->deliveryType,
            'deliveryAddress' => $this->deliveryAddress,
            'paymentMethod' => $this->paymentMethod,
            'items' => $this->items,
            'total' => $this->total,
            'createdAt' => $this->createdAt,
        ];
    }
}
