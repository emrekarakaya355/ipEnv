<?php

namespace App\Exceptions;

use Exception;

abstract class CustomException extends Exception
{

    public function __construct($message = "Internal Error", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}
