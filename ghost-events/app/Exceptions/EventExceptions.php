<?php

namespace App\Exceptions;

use Exception;

class PrivateEventException extends Exception
{
    public function __construct($message = 'Event is private.')
    {
        parent::__construct($message);
    }
}