<?php 

namespace App\interfaces;

use app\Dtos\AccountDto;
use app\Dtos\TransactionDto;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface TransactionServiceInterface
{
     public function modelQuery(): Builder;
     public function generateReference(): string;
     public function createTransaction(TransactionDto $transactionDto): Transaction;
     public function getTransactionByReference(string $reference): Transaction;
     public function getTransactionById(int $transactionId): Transaction;
     public function getTransactionByAccountNumber(int $accountNumber, Builder $builder): Builder;
     public function getTransactionByUserId(int $userId, Builder $builder): Builder;
     public function downloadTransaction(AccountDto $accountDto, Carbon $fromDate, Carbon $toDate): Collection;
}
