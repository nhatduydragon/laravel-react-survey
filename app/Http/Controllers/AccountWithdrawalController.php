<?php

namespace App\Http\Controllers;

use App\Dtos\WithdrawDto;
use App\Http\Requests\WithdrawRequest;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountWithdrawalController extends Controller
{
    public function __construct(private AccountService $accountService)
    {

    }

    public function store(WithdrawRequest $request)
    {
        $withdrawDto = new WithdrawDto();
        $withdrawDto->setAccountNumber( $request->input('account_number') );
        $withdrawDto->setAmount( $request->input('amount') );
        $withdrawDto->setDescription( $request->input('description') );
        $withdrawDto->setPin( $request->input('pin') );
        $withdrawDto->setCategory();
        
        $this->accountService->withdraw($withdrawDto);

        return $this->sendSuccess([], 'Withdraw Successfull');
    }
}
