<?php

namespace App\Services;

use App\Dtos\AccountDto;
use App\Dtos\DepositDto;
use App\Dtos\TransactionDto;
use App\Dtos\TransferDto;
use App\Dtos\UserDto;
use App\Dtos\WithdrawDto;
use App\Events\DepositEvent;
use App\Events\TransactionEvent;
use App\Events\WithdrawalEvent;
use App\Exceptions\AccountNumberExistsException;
use App\Exceptions\AmountToLowException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountNumberException;
use App\Exceptions\InvalidPinException;
use App\Exceptions\WithdrawalTooLowException;
use App\interfaces\AccountServiceInterface;
use App\Models\Account;
use App\Models\Transfer;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AccountService implements AccountServiceInterface
{
     public function __construct(
          private UserService $userService, 
          private TransactionService $transactionService,
          private TransferService $transferService
     )
     {
          
     }

     public function modelQuery(): Builder
     {
          return Account::query();
     }

     public function hasAccountNumber(UserDto $userDto): bool
     {
          return $this->modelQuery()->where('user_id', $userDto->getId())->exists();
     }

     public function createAccountNumber(UserDto $userDto): Account
     {
          if ( $this->hasAccountNumber($userDto) ) {
               throw new AccountNumberExistsException();
          }

          return $this->modelQuery()->create([
               'account_number' => substr($userDto->getPhoneNumber(), -10),
               'user_id'        => $userDto->getId(),
          ]);
     }

     public function getAccountByAccountNumber(string $accountNumber): Account 
     {
          
     }

     public function getAccountByUserId(int $userId): Account 
     {
          return $this->modelQuery()->where('user_id', $userId)->first();
     }

     public function getAccount(int|string $accountNumberOrUserId): Account
     {

     }

     public function deposit(DepositDto $depositDto): TransactionDto
     {
          $minimumDeposit = 500;
          if ( $depositDto->getAmount() < $minimumDeposit ) {
               throw new AmountToLowException($minimumDeposit);
          }

          // lock account for update
          try {
               DB::beginTransaction();
               $transactionDto = new TransactionDto();
               $accountQuery   = $this->modelQuery()->where( 'account_number', $depositDto->getAccountNumber() );
               $this->accountExist($accountQuery);

               /** @var Account $lockedAccount */
               $lockedAccount  = $accountQuery->lockForUpdate()->first();
               $accountDto     = AccountDto::fromModel($lockedAccount);
               $transactionDto = $transactionDto->forDeposit(
                    $accountDto,
                    $this->transactionService->generateReference(),
                    $depositDto->getAmount(),
                    $depositDto->getDescription()
               );

               event(new TransactionEvent($transactionDto, $accountDto, $lockedAccount));

               DB::commit();

               return $transactionDto;
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }

     public function withdraw(WithdrawDto $withdrawDto): TransactionDto
     {
          $minimumWithdrawl = 500;
          if ( $withdrawDto->getAmount() < $minimumWithdrawl ) {
               throw new WithdrawalTooLowException($minimumWithdrawl);
          }

          try {
               DB::beginTransaction();
               $accountQuery   = $this->modelQuery()->where( 'account_number', $withdrawDto->getAccountNumber() );
               $this->accountExist($accountQuery);

               /** @var Account $lockedAccount */
               $lockedAccount  = $accountQuery->lockForUpdate()->first();
               $accountDto     = AccountDto::fromModel($lockedAccount);

               if ( $this->userService->validatePin($accountDto->getUserId(), $withdrawDto->getPin() ) === false ) {
                    throw new InvalidPinException();
               }

               $this->canWithdraw($accountDto, $withdrawDto);
               $transactionDto = new TransactionDto();
               $transactionDto = $transactionDto->forWithdrawl(
                    $accountDto, 
                    $this->transactionService->generateReference(), 
                    $withdrawDto
               );

               event(new TransactionEvent($transactionDto, $accountDto, $lockedAccount));

               DB::commit();

               return $transactionDto;
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }

     public function accountExist(Builder $accountQuery): void
     {
          if ( $accountQuery->exists() === false) {
               throw new InvalidAccountNumberException();
          }
     }

     public function canWithdraw(AccountDto $accountDto, WithdrawDto $withdrawDto): bool
     {
          // if the account is blocked
          // if user has not exceed thir transaction limit for the day or month
          // if amount to withdrawal greater than user balance
          if ( $accountDto->getBalance() < $withdrawDto->getAmount() ) {
               throw new InsufficientBalanceException();
          }
          // if amount left after withdrawal is not more than the minimum account balance

          return true;
     }

     public function transter(string $senderAccountNumber, string $recieverAccountNumer, string $senderAccountNumberPin, int|float $amount, string $description = null): TransferDto
     {
          if ($senderAccountNumber == $recieverAccountNumer) {
               throw new Exception('Receiver and Sender Account number can not be the same');
          }

          $minimumWithdrawl = 500;

          try {
               DB::beginTransaction();
               $senderAccountQuery     = $this->modelQuery()->where( 'account_number', $senderAccountNumber );
               $recieverAccountQuery   = $this->modelQuery()->where( 'account_number', $recieverAccountNumer );
               $this->accountExist($senderAccountQuery);
               $this->accountExist($recieverAccountQuery);

               /** @var Account $lockedSenderAccount */
               $lockedSenderAccount  = $senderAccountQuery->lockForUpdate()->first();
               $senderAccountDto     = AccountDto::fromModel($lockedSenderAccount);

               /** @var Account $lockedRecieverAccount */
               $lockedRecieverAccount  = $recieverAccountQuery->lockForUpdate()->first();
               $recieverAccountDto     = AccountDto::fromModel($lockedRecieverAccount);

               if ( $this->userService->validatePin($senderAccountDto->getUserId(), $senderAccountNumberPin ) === false ) {
                    throw new InvalidPinException();
               }

               $withdrawDto = new WithdrawDto();
               $depositDto  = new DepositDto();
               $transferDto = new TransferDto();

               $withdrawDto->setAccountNumber( $senderAccountDto->getAccountNumber() );
               $withdrawDto->setAmount( $amount );
               $withdrawDto->setDescription( '' );
               $withdrawDto->setPin( $senderAccountNumberPin );
               $withdrawDto->setCategory();

               $depositDto->setAccountNumber( $recieverAccountDto->getAccountNumber() );
               $depositDto->setAmount( $amount );
               $depositDto->setDescription( '' );
               $depositDto->setCategory();

               $this->canWithdraw($senderAccountDto, $withdrawDto);

               $transactionDto = new TransactionDto();
               $transactionWithdrawlDto = $transactionDto->forWithdrawl(
                    $senderAccountDto, 
                    $this->transactionService->generateReference(), 
                    $withdrawDto
               );

               $transactionDepositDto = $transactionDto->forDeposit(
                    $recieverAccountDto,
                    $this->transactionService->generateReference(),
                    $depositDto->getAmount(),
                    $depositDto->getDescription()
               );

               $transferDto->setReference( $this->transferService->generateReference() );

               $transferDto->setSenderId( $senderAccountDto->getId() );
               $transferDto->setSenderAccountId( $senderAccountDto->getId() );

               $transferDto->setRecepientId( $recieverAccountDto->getId() );
               $transferDto->setRecepientAccountId( $recieverAccountDto->getId() );

               $transferDto->setAmount( $amount );
               $transferDto->setStatus( 'success' );

               $transfer = $this->transferService->createTransfer( $transferDto );

               $transactionWithdrawlDto->setTransferId( $transfer->id );
               $transactionDepositDto->setTransferId( $transfer->id );

               // Withdrawl
               event(new TransactionEvent($transactionWithdrawlDto, $senderAccountDto, $lockedSenderAccount));
               // Deposit
               event(new TransactionEvent($transactionDepositDto, $recieverAccountDto, $lockedRecieverAccount));

               DB::commit();

               return $transferDto;
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
}
