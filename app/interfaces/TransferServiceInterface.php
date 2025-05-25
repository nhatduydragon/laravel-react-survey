<?php 

namespace App\interfaces;

use App\Dtos\AccountDto;
use App\Dtos\TransferDto;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;

interface TransferServiceInterface
{
     public function modelQuery(): Builder;
     public function createTransfer(TransferDto $transferDto): Transfer;
     public function getTransferBetweenAccount(AccountDto $firstAccountDto, AccountDto $secondAccountDto): array;
     public function generateReference(): string;
     public function getTransferById(int $transferId): Transfer;
     public function getTransferByReference(string $reference): Transfer;
}