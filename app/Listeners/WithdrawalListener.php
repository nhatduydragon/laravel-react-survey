<?php

namespace App\Listeners;

use App\Enum\TranscactionCategoryEnum;
use App\Events\TransactionEvent;
use App\Events\WithdrawalEvent;
use app\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WithdrawalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private TransactionService $transactionService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransactionEvent $event)
    {
        if ($event->transactionDto->getCategory() != TranscactionCategoryEnum::WITHDRAWAL) {
            return;
        }

        $this->transactionService->createTransaction($event->transactionDto);
        $account          = $event->lockedAccount;
        $account->balance = $account->balance - $event->transactionDto->getAmount();
        $account->save();
        $account          = $account->refresh();
        $this->transactionService->updateTransactionBalance( $event->transactionDto->getReference(), $account->balance );

        if ( $event->transactionDto->getTransferId() ) {
            $this->transactionService->updateTransferId( $event->transactionDto->getReference(), $event->transactionDto->getTransferId() );
        }
    }
}
