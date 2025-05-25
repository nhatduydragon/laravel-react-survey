<?php

namespace App\Dtos;

use App\Enum\TranscactionCategoryEnum;
use Carbon\Carbon;

class TransactionDto
{
     private int|null $id;
     private string $reference;
     private int $user_id;
     private int $account_id;
     private int|null $transfer_id;
     private float $amount;
     private float $balance;
     private string $category;
     private string|null $description;
     private Carbon $date;
     private bool $confirm;
     private Carbon $created_at;
     private Carbon $updated_at;
     private string|null $meta;

     // ID
     public function getId(): int|null
     {
          return $this->id;
     }

     public function setId(int|null $id): void
     {
          $this->id = $id;
     }

     // Reference
     public function getReference(): string
     {
          return $this->reference;
     }

     public function setReference(string $reference): void
     {
          $this->reference = $reference;
     }

     // User ID
     public function getUserId(): int
     {
          return $this->user_id;
     }

     public function setUserId(int $user_id): void
     {
          $this->user_id = $user_id;
     }

     // Account ID
     public function getAccountId(): int
     {
          return $this->account_id;
     }

     public function setAccountId(int $account_id): void
     {
          $this->account_id = $account_id;
     }

     // Transfer ID
     public function getTransferId(): int|null
     {
          return $this->transfer_id;
     }

     public function setTransferId(int|null $transfer_id): void
     {
          $this->transfer_id = $transfer_id;
     }

     // Amount
     public function getAmount(): float
     {
          return $this->amount;
     }

     public function setAmount(float $amount): void
     {
          $this->amount = $amount;
     }

     // Balance
     public function getBalance(): float
     {
          return $this->balance;
     }

     public function setBalance(float $balance): void
     {
          $this->balance = $balance;
     }

     // Category
     public function getCategory(): string
     {
          return $this->category;
     }

     public function setCategory(string $category): void
     {
          $this->category = $category;
     }

     // Description
     public function getDescription(): string|null
     {
          return $this->description;
     }

     public function setDescription(string|null $description): void
     {
          $this->description = $description;
     }

     // Date
     public function getDate(): Carbon
     {
          return $this->date;
     }

     public function setDate(Carbon $date): void
     {
          $this->date = $date;
     }

     // Confirm
     public function isConfirmed(): bool
     {
          return $this->confirm;
     }

     public function setConfirm(bool $confirm): void
     {
          $this->confirm = $confirm;
     }

     // Created At
     public function getCreatedAt(): Carbon
     {
          return $this->created_at;
     }

     public function setCreatedAt(Carbon $created_at): void
     {
          $this->created_at = $created_at;
     }

     // Updated At
     public function getUpdatedAt(): Carbon
     {
          return $this->updated_at;
     }

     public function setUpdatedAt(Carbon $updated_at): void
     {
          $this->updated_at = $updated_at;
     }

     // Meta
     public function getMeta(): string|null
     {
          return $this->meta;
     }

     public function setMeta(string|null $meta): void
     {
          $this->meta = $meta;
     }

     public function forDeposit(AccountDto $accountDto, string $reference, float|int $amount, string|null $description): self
     {
          $dto = new TransactionDto();
          $dto->setUserId( $accountDto->getUserId() );
          $dto->setReference( $reference );
          $dto->setAccountId( $accountDto->getId() );
          $dto->setAmount( $amount );
          $dto->setTransferId( null );
          $dto->setCategory( TranscactionCategoryEnum::DEPOSIT );
          $dto->setDate( Carbon::now() );
          $dto->setDescription( $description );

          return $dto;
     }

     public function forWithdrawl(AccountDto $accountDto, string $reference, WithdrawDto $withdrawDto): self
     {
          $dto = new TransactionDto();
          $dto->setUserId( $accountDto->getUserId() );
          $dto->setReference( $reference );
          $dto->setAccountId( $accountDto->getId() );
          $dto->setAmount( $withdrawDto->getAmount() );
          $dto->setTransferId( null );
          $dto->setCategory( TranscactionCategoryEnum::WITHDRAWAL );
          $dto->setDate( Carbon::now() );
          $dto->setDescription( $withdrawDto->getDescription() );

          return $dto;
     }

     public function forDepositToArray(): array
     {
          return [
               'user_id'      => $this->getUserId(),
               'reference'    => $this->getReference(),
               'account_id'   => $this->getAccountId(),
               'amount'       => $this->getAmount(),
               'category'     => $this->getCategory(),
               'date'         => $this->getDate(),
               'description'  => $this->getDescription(),
          ];
     }

     public function forWithdrawalToArray(): array
     {
          return [
               'user_id'      => $this->getUserId(),
               'reference'    => $this->getReference(),
               'account_id'   => $this->getAccountId(),
               'amount'       => $this->getAmount(),
               'category'     => $this->getCategory(),
               'date'         => $this->getDate(),
               'description'  => $this->getDescription(),
          ];
     }
}
