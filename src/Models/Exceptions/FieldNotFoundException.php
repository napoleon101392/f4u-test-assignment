<?php

namespace Napoleon\Models\Exceptions;

class FieldNotFoundException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}