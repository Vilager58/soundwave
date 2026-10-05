<?php

namespace App\Brand;

/**
 * Данные бренда
 */
readonly class BrandData
{
    public function __construct(
        public int    $id,
        public string $title,
        public string $slug,
        public string $country,
        public string $logoUrl,
        public string $description,
        public bool   $active,
        public ?int   $amount = null
    )
    {
    }
}
