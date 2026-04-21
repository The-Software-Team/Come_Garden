<?php

namespace App\Exceptions;

use Exception;

class InsufficientCreditsException extends Exception
{
    protected $message = "Insufficient seed credits";
}