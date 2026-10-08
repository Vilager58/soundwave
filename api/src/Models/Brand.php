<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\Brand\BrandData;

class Brand
{
    private int $id;
    private string $title;
    private string $slug;
    private string $country;
    private string $logoUrl;
    private string $description;
    private bool $active;
    private int $amount;

    public function __construct($id, $title, $slug, $country, $logoUrl, $description, $active, $amount)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->country = $country;
        $this->logoUrl = $logoUrl;
        $this->description = $description;
        $this->active = $active;
        $this->amount = $amount;
    }

    public function getId(): int {
        return $this->id;
    }
    public function getTitle(): string {
        return $this->title;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function getCountry(): string {
        return $this->country;
    }

    public function getLogoUrl(): string {
        return $this->logoUrl;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function update(BrandData $brand): void {
            $this->title = $brand->title;
            $this->slug = $brand->slug;
            $this->country = $brand->country;
            $this->logoUrl = $brand->logoUrl;
            $this->description = $brand->description;
            $this->active = $brand->active;
    }

}