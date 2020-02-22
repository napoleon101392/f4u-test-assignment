<?php

namespace Tests\Services;

use Tests\TestCase;
use Napoleon\Services\DataService;

class DataServiceTest extends TestCase
{
    /** @test */
    function should_get_a_record()
    {
        $dataService = new DataService(__DIR__ . '/../../database.test.json');

        $data = $dataService->driver()->get();

        $this->assertNotNull($data);
    }
}
