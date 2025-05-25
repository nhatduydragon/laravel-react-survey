<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{    
    public function __construct(private AccountService $accountService)
    {

    }

    public function store(TransferRequest $request): JsonResponse
    {
        $user = $request->user();
        $senderAccount = $this->accountService->getAccountByUserId( $user->id );

        $transferDto = $this->accountService->transter(
            $senderAccount->account_number,
            $request->input( 'receiver_account_number' ),
            $request->input( 'pin' ),
            $request->input( 'amount' ),
            $request->input( 'description' ),
        );

        return $this->sendSuccess([ 'transfer' => $transferDto ], 'Account Transfer in Progress');
    }
}
