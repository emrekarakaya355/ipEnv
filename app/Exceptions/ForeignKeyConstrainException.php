<?php

namespace App\Exceptions;

use Exception;

class ForeignKeyConstrainException extends CustomException
{
    public function __construct($message = "Kayıtlı Bir Cihaz Tarafından Kullanıldığı İçin Silinemez!!!", $code = 409, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
