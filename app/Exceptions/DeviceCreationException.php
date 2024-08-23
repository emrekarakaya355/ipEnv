<?php

namespace App\Exceptions;

use Exception;

class DeviceCreationException extends Exception
{
    public function __construct($message = "Device creation failed", $code = 1005, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
