<?php

namespace App\interfaces;

use App\Dtos\AccountDto;
use App\Dtos\TransactionDto;
use App\Dtos\UserDto;
use App\Dtos\DepositDto;
use App\Dtos\TransferDto;
use App\Dtos\WithdrawDto;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;

interface AccountServiceInterface 
{
     public function modelQuery(): Builder;
     public function createAccountNumber(UserDto $userDto): Account;
     public function getAccountByAccountNumber(string $accountNumber): Account;
     public function getAccountByUserId(int $userId): Account;
     public function getAccount(int|string $accountNumberOrUserId): Account;
     public function deposit(DepositDto $depositDto): TransactionDto;
     public function withdraw(WithdrawDto $withdraw): TransactionDto;
     public function transter(string $senderAccountNumber, string $recieverAccountNumer, string $senderAccountNumberPin, int|float $amount, string $description = null): TransferDto;
     public function accountExist(Builder $accountQuery): void;
     public function canWithdraw(AccountDto $accountDto, WithdrawDto $withdrawDto): bool;
}
