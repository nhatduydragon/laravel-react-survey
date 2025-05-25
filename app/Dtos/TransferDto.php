<?php 

namespace App\Dtos;

use Carbon\Carbon;

class TransferDto
{
     private ?int $id;
     private int $sender_id;
     private int $sender_account_id;
     private int $recepient_id;
     private int $recepient_account_id;
     private float|int $amount;
     private string $status;
     private string $reference;
     private ?Carbon $created_at;
     private ?Carbon $updated_at;

     // Accessor and Mutator for $id
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // Accessor and Mutator for $sender_id
    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    public function setSenderId(int $sender_id): void
    {
        $this->sender_id = $sender_id;
    }

    // Accessor and Mutator for $sender_account_id
    public function getSenderAccountId(): int
    {
        return $this->sender_account_id;
    }

    public function setSenderAccountId(int $sender_account_id): void
    {
        $this->sender_account_id = $sender_account_id;
    }

    // Accessor and Mutator for $recepient_id
    public function getRecepientId(): int
    {
        return $this->recepient_id;
    }

    public function setRecepientId(int $recepient_id): void
    {
        $this->recepient_id = $recepient_id;
    }

    // Accessor and Mutator for $recepient_account_id
    public function getRecepientAccountId(): int
    {
        return $this->recepient_account_id;
    }

    public function setRecepientAccountId(int $recepient_account_id): void
    {
        $this->recepient_account_id = $recepient_account_id;
    }

    // Accessor and Mutator for $amount
    public function getAmount(): float|int
    {
        return $this->amount;
    }

    public function setAmount(float|int $amount): void
    {
        $this->amount = $amount;
    }

    // Accessor and Mutator for $status
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    // Accessor and Mutator for $reference
    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    // Accessor and Mutator for $created_at
    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function setCreatedAt(?Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    // Accessor and Mutator for $updated_at
    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
