<?php

use Tests\TestCase;
use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Napoleon\Repositories\Exceptions\DefaultAddressNotRemovableException;

class DefaultAddressNotRemovableExceptionTest extends TestCase
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
    function should_not_delete_default_address()
    {
        $this->expectException(DefaultAddressNotRemovableException::class);

        // Create dummy record
        $this->repository->addShippingAddress([
            "client_id"  => "1",
            "country"    => "Philippines",
            "city"       => "Manila",
            "zipcode"    => "1008",
            "street"     => "Sampaloc",
            "is_default" => true,
        ]);

        $this->repository->addShippingAddress([
            "client_id"  => "1",
            "country"    => "Philippines",
            "city"       => "Manila",
            "zipcode"    => "1008",
            "street"     => "Sampaloc",
            "is_default" => false,
        ]);

        $this->repository->removeAddress(1, 1);
    }
}
