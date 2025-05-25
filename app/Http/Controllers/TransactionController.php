<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
        
    }

    public function index(FilterTransactionRequest $request)
    {
        $user               = $request->user();
        $transactionBuilder = $this->transactionService->modelQuery()
            ->when( $request->query( 'category' ), function( $query, $category ) {
                $query->where( 'category', $category );
            })->when( $request->query( 'start_date' ), function( $query, $startDate ) use($request) {
                $endDate = $request->query( 'end_date' );
                if (($startDate && $endDate) == false) {
                    return $query;
                }

                $query->whereDate( 'date', '>=', $startDate);
                $query->whereDate( 'date', '<=', $endDate);
            });

        $transactionBuilder = $this->transactionService->getTransactionByUserId( $user->id, $transactionBuilder );

        return $this->sendSuccess( [ 'transaction' => $transactionBuilder->paginate($request->query( 'per_page', 15 )) ] );
    }

    public function getTransactionByReference(string $reference): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionByReference( $reference );

        return $this->sendSuccess([ 'transaction' => $transaction ]);
    }
}
