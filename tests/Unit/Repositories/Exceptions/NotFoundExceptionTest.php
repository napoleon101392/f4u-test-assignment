<?php

namespace Tests\Repositories\Exceptions;

use Tests\TestCase;
use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Napoleon\Repositories\Exceptions\NotFoundException;

class NotFoundExceptionTest extends TestCase
{
    protected $dataService;

    protected $repository;

    public function setUp():void
    {
        parent::setUp();

        $this->dataService = new DataService(__DIR__ . '/../../../database.test.json');

        $this->repository = new ClientRepository($this->dataService);
    }

    /** @test */
    function client_not_found_exception()
    {
        $this->expectException(NotFoundException::class);

        $creation = $this->repository->addShippingAddress([
            "client_id" => "999",
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);
    }
}
