<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

class Product
{
    private int $id;
    private int $categoryId;
    private int $brandId;
    private string $title;
    private string $slug;
    private ?string $modelCode;
    private ?string $shortDescription;
    private string $description;
    private bool $isActive;
    private bool $isFeatured;
    private array $variants;

    public function __construct(
        int $id,
        int $categoryId,
        int $brandId,
        string $title,
        string $slug,
        ?string $modelCode = null,
        ?string $shortDescription = null,
        string $description = '',
        bool $isActive = true,
        bool $isFeatured = false,
        array $variants = []
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->brandId = $brandId;
        $this->title = $title;
        $this->slug = $slug;
        $this->modelCode = $modelCode;
        $this->shortDescription = $shortDescription;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->isFeatured = $isFeatured;
        $this->variants = $variants;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getModelCode(): ?string
    {
        return $this->modelCode;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function setVariants(array $variants): void
    {
        $this->variants = $variants;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'brandId' => $this->brandId,
            'title' => $this->title,
            'slug' => $this->slug,
            'modelCode' => $this->modelCode,
            'shortDescription' => $this->shortDescription,
            'description' => $this->description,
            'isActive' => $this->isActive,
            'isFeatured' => $this->isFeatured,
            'variants' => $this->variants,
        ];
    }
}