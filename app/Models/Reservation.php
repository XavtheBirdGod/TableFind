<?php
declare(strict_types=1);

namespace App\Models;

class Reservation
{
    private int $id;
    private string $customerName;
    private ?string $customerEmail;
    private string $customerPhone;
    private int $guestCount;
    private string $reservationDate;
    private string $reservationTime;
    private ?int $tableId;
    private string $status;
    private ?string $notes;
    // Helper property for table number (from join)
    private ?string $tableNumber = null;

    public function __construct(
        int $id = 0,
        string $customerName = '',
        ?string $customerEmail = null,
        string $customerPhone = '',
        int $guestCount = 1,
        string $reservationDate = '',
        string $reservationTime = '',
        ?int $tableId = null,
        string $status = 'pending',
        ?string $notes = null
    ) {
        $this->id = $id;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->customerPhone = $customerPhone;
        $this->guestCount = $guestCount;
        $this->reservationDate = $reservationDate;
        $this->reservationTime = $reservationTime;
        $this->tableId = $tableId;
        $this->status = $status;
        $this->notes = $notes;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getCustomerName(): string { return $this->customerName; }
    public function getCustomerEmail(): ?string { return $this->customerEmail; }
    public function getCustomerPhone(): string { return $this->customerPhone; }
    public function getGuestCount(): int { return $this->guestCount; }
    public function getReservationDate(): string { return $this->reservationDate; }
    public function getReservationTime(): string { return $this->reservationTime; }
    public function getTableId(): ?int { return $this->tableId; }
    public function getStatus(): string { return $this->status; }
    public function getNotes(): ?string { return $this->notes; }
    public function getTableNumber(): ?string { return $this->tableNumber; }

    // Setters
    public function setTableNumber(?string $number): void { $this->tableNumber = $number; }
    
    // Allow updating fields for editing
    public function updateDetails(
        string $name, ?string $email, string $phone, 
        int $guests, string $date, string $time, ?int $tableId, string $status, ?string $notes
    ): void {
        $this->customerName = $name;
        $this->customerEmail = $email;
        $this->customerPhone = $phone;
        $this->guestCount = $guests;
        $this->reservationDate = $date;
        $this->reservationTime = $time;
        $this->tableId = $tableId;
        $this->status = $status;
        $this->notes = $notes;
    }
}
