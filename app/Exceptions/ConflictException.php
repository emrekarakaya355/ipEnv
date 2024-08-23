<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends CustomException
{
    public function __construct($message = "Resource conflict", $code = 409, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
