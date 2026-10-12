<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

class Category
{
    private int $id;
    private string $title;
    private string $slug;
    private ?int $parentId;
    private ?string $parentTitle;
    private ?string $description;
    private bool $isActive;
    private int $amount;

    public function __construct(
        int $id,
        string $title,
        string $slug,
        ?int $parentId = null,
        ?string $parentTitle = null,
        ?string $description = null,
        bool $isActive = true,
        int $amount = 0
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->parentId = $parentId;
        $this->parentTitle = $parentTitle;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function getParentTitle(): ?string
    {
        return $this->parentTitle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'parentId' => $this->parentId,
            'parentTitle' => $this->parentTitle,
            'description' => $this->description,
            'isActive' => $this->isActive ? 1 : 0,
            'amount' => $this->amount,
        ];
    }
}
