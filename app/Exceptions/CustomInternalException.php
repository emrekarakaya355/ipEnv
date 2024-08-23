<?php

namespace App\Exceptions;

use Exception;

class CustomInternalException extends CustomException
{
    public function __construct($message = "Internal Error", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}
