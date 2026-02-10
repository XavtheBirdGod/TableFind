<?php
declare(strict_types=1);

namespace App\Models;

class Table
{
    private int $id;
    private string $tableNumber;
    private int $capacity;
    private string $status;

    public function __construct(
        int $id = 0,
        string $tableNumber = '',
        int $capacity = 2,
        string $status = 'available'
    ) {
        $this->id = $id;
        $this->tableNumber = $tableNumber;
        $this->capacity = $capacity;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTableNumber(): string
    {
        return $this->tableNumber;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setTableNumber(string $number): void
    {
        $this->tableNumber = $number;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
