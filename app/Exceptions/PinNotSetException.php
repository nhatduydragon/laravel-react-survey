<?php

namespace App\Exceptions;

use Exception;

class PinNotSetException extends Exception
{
    public function __construct() 
    {
        parent::__construct('Please set your pin');
    }
}
