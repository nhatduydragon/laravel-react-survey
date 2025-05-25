<?php 

namespace App\Services;

use App\interfaces\TransferServiceInterface;
use App\Models\Transfer;
use App\Dtos\AccountDto;
use App\Dtos\TransferDto;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferService implements TransferServiceInterface
{
     public function modelQuery(): Builder
     {
          return Transfer::query();
     }

     public function createTransfer(TransferDto $transferDto): Transfer
     {
          /** @var Transfer $transfer */
          $transfer = $this->modelQuery()->create([
               'sender_id'              => $transferDto->getSenderId(),
               'recipient_id'           => $transferDto->getRecepientId(),
               'sender_account_id'      => $transferDto->getSenderAccountId(),
               'recipient_account_id'   => $transferDto->getRecepientAccountId(),
               'reference'              => $transferDto->getReference(),
               'status'                 => $transferDto->getStatus(),
               'amount'                 => $transferDto->getAmount(),
          ]);

          return $transfer;
     }

     public function getTransferBetweenAccount(AccountDto $firstAccountDto, AccountDto $secondAccountDto): array
     {

     }

     public function generateReference(): string
     {
          return Str::upper('TRF' . '/' . Carbon::now()->getTimestampMs() . '/' . Str::random(4));
     }

     public function getTransferById(int $transferId): Transfer
     {
          /** @var Transfer $transfer */
          $transfer = $this->modelQuery()->where('id', $transferId)->first();
          if ( !$transfer ) {
               throw new ModelNotFoundException('Transfer not Found');
          }

          return $transfer;
     }

     public function getTransferByReference(string $reference): Transfer
     {
          /** @var Transfer $transfer */
          $transfer = $this->modelQuery()->where('reference', $reference)->first();
          if ( !$transfer ) {
               throw new ModelNotFoundException('Transfer with supplier reference not Found');
          }

          return $transfer;
     }
}
