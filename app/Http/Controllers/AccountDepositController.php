<?php

namespace App\Http\Controllers;

use App\Dtos\DepositDto;
use App\Http\Requests\DepositRequest;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountDepositController extends Controller
{
    public function __construct(private AccountService $accountService)
    {

    }

    public function store(DepositRequest $request): JsonResponse
    {
        $depositDto = new DepositDto();
        $depositDto->setAccountNumber( $request->input('account_number') );
        $depositDto->setAmount( $request->input('amount') );
        $depositDto->setDescription( $request->input('description') );
        $depositDto->setCategory();
        
        $this->accountService->deposit($depositDto);

        return $this->sendSuccess([], 'Deposit Successfull');
    }
}
