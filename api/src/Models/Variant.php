<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

class Variant
{
    private int $id;
    private int $productId;
    private string $title;
    private string $article;
    private ?string $barcode;
    private float $price;
    private ?float $oldPrice;
    private int $amount;
    private bool $isActive;
    private array $attributes;

    public function __construct(
        int $id,
        int $productId,
        string $title,
        string $article,
        ?string $barcode = null,
        float $price = 0.0,
        ?float $oldPrice = null,
        int $amount = 0,
        bool $isActive = true,
        array $attributes = []
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->title = $title;
        $this->article = $article;
        $this->barcode = $barcode;
        $this->price = $price;
        $this->oldPrice = $oldPrice;
        $this->amount = $amount;
        $this->isActive = $isActive;
        $this->attributes = $attributes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getOldPrice(): ?float
    {
        return $this->oldPrice;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'title' => $this->title,
            'article' => $this->article,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'oldPrice' => $this->oldPrice,
            'amount' => $this->amount,
            'isActive' => $this->isActive,
            'attributes' => $this->attributes,
        ];
    }
}
