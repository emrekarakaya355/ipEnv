<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends CustomException
{
    public function __construct($message = "Model not found", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
