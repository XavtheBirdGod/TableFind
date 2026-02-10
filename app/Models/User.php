<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $passwordHash;
    private int $roleId;

    public function __construct(
        int $id = 0, 
        string $name = '', 
        string $email = '', 
        string $passwordHash = '', 
        int $roleId = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roleId = $roleId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    // Setters for updating profile
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $hash): void
    {
        $this->passwordHash = $hash;
    }
}
