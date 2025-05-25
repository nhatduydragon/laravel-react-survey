<?php 

namespace App\Exceptions;

class AmountToLowException extends \Exception
{
     public function __construct($minAmount)
     {
          parent::__construct('Deposit amount must be greater than ' . $minAmount);
     }
}