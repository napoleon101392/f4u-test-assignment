<?php

namespace Tests\Utils\FileManager\Exceptions;

use Tests\TestCase;
use Napoleon\Utils\FileManager\LocalManager;
use Napoleon\Utils\FileManager\Exceptions\FileException;

class FileExceptionTest extends TestCase
{
    /**
     * @test
     */
    function file_not_found_exception()
    {
        $this->expectException(FileException::class);

        $resolver = new LocalManager('/fake-file-path.ext');

        $resolver->get();
    }
}
