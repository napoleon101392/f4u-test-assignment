<?php

namespace Napoleon\Utils\FileManager\Exceptions;

use Exception;

class FileException extends Exception
{
    /**
     * Undocumented function
     *
     * @param string $path
     * @param string $message
     */
    public function __construct($path = null, $message = 'Cant find File')
    {
        parent::__construct($message . $path);
    }
}
