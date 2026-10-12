<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

class Attribute
{
    private int $id;
    private string $code;
    private string $title;
    private ?string $unit;
    private string $dataType;
    private bool $isFilterable;

    public function __construct(
        int $id,
        string $code,
        string $title,
        ?string $unit = null,
        string $dataType = 'string',
        bool $isFilterable = true
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->title = $title;
        $this->unit = $unit;
        $this->dataType = $dataType;
        $this->isFilterable = $isFilterable;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function isFilterable(): bool
    {
        return $this->isFilterable;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'unit' => $this->unit,
            'dataType' => $this->dataType,
            'isFilterable' => $this->isFilterable,
        ];
    }
}
