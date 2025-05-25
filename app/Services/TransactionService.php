<?php 

namespace app\Services;

use app\Dtos\AccountDto;
use app\Dtos\TransactionDto;
use App\Enum\TranscactionCategoryEnum;
use App\interfaces\TransactionServiceInterface;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TransactionService implements TransactionServiceInterface
{     
     public function modelQuery(): Builder
     {
          return Transaction::query();
     }

     public function generateReference(): string
     {
          return Str::upper('TF' . '/' . Carbon::now()->getTimestampMs() . '/' . Str::random(4));
     }

     public function createTransaction(TransactionDto $transactionDto): Transaction
     {
          $data = [];
          if ( $transactionDto->getCategory() == TranscactionCategoryEnum::DEPOSIT ) {
               $data = $transactionDto->forDepositToArray();
          }

          if ( $transactionDto->getCategory() == TranscactionCategoryEnum::WITHDRAWAL ) {
               $data = $transactionDto->forWithdrawalToArray();
          }

          /** @var Transaction $transaction */
          $transaction = $this->modelQuery()->create($data);
          return $transaction;
     }
     
     public function getTransactionByReference(string $reference): Transaction
     {
          return $this->modelQuery()->where( 'reference', $reference )->firstOrFail();
     }

     public function getTransactionById(int $transactionId): Transaction
     {
          return $this->modelQuery()->where( 'id', $transactionId )->firstOrFail();
     }

     public function getTransactionByAccountNumber(int $accountNumber, Builder $builder): Builder
     {
          return $builder->whereHas( 'account', function($query) use($accountNumber) {
               $query->where( 'account_number', $accountNumber );
          });
     }

     public function getTransactionByUserId(int $userId, Builder $builder): Builder
     {
          return $builder->where( 'user_id', $userId );
     }

     public function downloadTransaction(AccountDto $accountDto, Carbon $fromDate, Carbon $toDate): Collection
     {

     }

     public function updateTransactionBalance(string $reference, float|int $balance)
     {
          $this->modelQuery()->where('reference', $reference)->update([
               'balance'   => $balance,
               'confirmed' => true,
          ]);
     }

     public function updateTransferId(string $reference, int $transferId)
     {
          $this->modelQuery()->where('reference', $reference)->update([
               'transfer_id'   => $transferId,
          ]);
     }

}
