<?php 

namespace App\Dtos;

use App\Enum\TranscactionCategoryEnum;

class WithdrawDto
{
     private string $account_number; // withdrawal account number
     private int|float $amount;
     private string|null $description;
     private string $category;
     private string $pin;


     public function getAccountNumber(): string
     {
          return $this->account_number;
     }

     public function getAmount(): int|float 
     {
          return $this->amount;
     }

     public function getDescription(): string|null 
     {
          return $this->description;
     }

     public function getCategory(): string 
     {
          return $this->category;
     }

     public function getPin(): string
     {
          return $this->pin;
     }

     public function setAccountNumber(string $account_number): void 
     {
          $this->account_number = $account_number;
     }

     public function setAmount(int|float $amount): void
     {
          $this->amount = $amount;
     }

     public function setDescription(string $description): void 
     {
          $this->description = $description;
     }

     public function setCategory(): void
     {
          $this->category = TranscactionCategoryEnum::WITHDRAWAL;
     }

     public function setPin(string $pin): void
     {
          $this->pin = $pin;
     }
}
