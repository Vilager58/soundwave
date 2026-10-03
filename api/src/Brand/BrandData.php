<?php
readonly class BrandData
{
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private string $country,
        private string $logoUrl,
        private string $description,
        private bool $Active,
        private ?int $amount,
    ) {
    }
}