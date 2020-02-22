<?php

namespace Tests\Repositories\Exceptions;

use Tests\TestCase;
use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Napoleon\Repositories\Exceptions\MaximumAddressCountException;

class MaximumAddressCountExceptionTest extends TestCase
{
    protected $dataService;

    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->dataService = new DataService(__DIR__ . '/../../../database.test.json');

        $this->repository = new ClientRepository($this->dataService);
    }

    /** @test */
    public function maxium_address_acount_exception()
    {
        $this->expectException(MaximumAddressCountException::class);

        for ($i=0; $i <= 3; $i++) {
            $this->repository->addShippingAddress([
                "client_id" => "1",
                "country"   => "Philippines",
                "city"      => "Manila",
                "zipcode"   => "1008",
                "street"    => "Sampaloc",
            ]);
        }
    }
}
