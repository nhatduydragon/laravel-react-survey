<?php

namespace App\Exceptions;

use Exception;

class WithdrawalTooLowException extends Exception
{
    public function __construct($minAmount)
     {
        parent::__construct('Withdrawl amount must be greater than ' . $minAmount);
     }
}
