<?php

namespace App\Exceptions;

use Exception;

class PortConflictException extends CustomException
{
    public function __construct($message = 'Bu port başka bir cihaz tarafından kullanılıyor', $code = 409, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}
