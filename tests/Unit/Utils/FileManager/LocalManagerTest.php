<?php

namespace Tests\Utils\FileManager;

use Tests\TestCase;
use Napoleon\Utils\FileManager\LocalManager;

class LocalManagerTest extends TestCase
{
    /**
     * @test
     */
    function succeed_to_fetch_file()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . "test.json";

        $data = new LocalManager($path);

        $this->assertTrue(!is_null($data->get()->json));
    }
}
