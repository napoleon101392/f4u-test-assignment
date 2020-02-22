<?php

namespace Tests;

use Napoleon\Utils\FileManager\LocalManager;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $resetFile;

    protected $resetPath;

    public function setUp(): void
    {
        parent::setUp();

        $this->resetPath = __DIR__ . DIRECTORY_SEPARATOR . "database.test.json";

        $this->resetFile = new LocalManager($this->resetPath);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $data = \file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'database.stub');

        \file_put_contents($this->resetPath, $data);
    }
}
