<?php

namespace Napoleon\Repositories\Exceptions;

class MaximumAddressCountException extends \Exception
{
    public function __construct($message = 'Address Maxedout')
    {
        parent::__construct($message);
    }
}
