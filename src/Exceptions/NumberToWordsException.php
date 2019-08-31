<?php

namespace MilanTarami\NumberToWordsConverter\Exceptions;

use Exception;

class NumberToWordsException extends Exception
{

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
    
    public function report()
    {
        throw new Exception($this->message);
    }

}
