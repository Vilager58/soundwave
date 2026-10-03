<?php
namespace App;
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
}