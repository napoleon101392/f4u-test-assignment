<?php

namespace Tests\Repositories;

use Tests\TestCase;
use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;

class ClientRepositoryTest extends TestCase
{
    protected $dataService;

    protected $repository;

    public function setUp():void
    {
        parent::setUp();

        $this->dataService = new DataService(__DIR__ . '/../../database.test.json');

        $this->repository = new ClientRepository($this->dataService);
    }

    /** @test */
    function should_return_all_clients()
    {
        $this->assertNotNull($this->repository->getClients());
    }

    /** @test */
    function can_find_address_using_client_id()
    {
        $clientId = "2";
        $this->repository->addShippingAddress([
            "client_id" => "1",
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $this->repository->addShippingAddress([
            "client_id" => $clientId,
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $this->repository->addShippingAddress([
            "client_id" => $clientId,
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $result = $this->repository->getAddressesByClient($clientId);

        $this->assertTrue(count($result) == 2);
    }

    /** @test */
    function should_add_addresses()
    {
        $creation = $this->repository->addShippingAddress([
            "client_id" => "1",
            "country" => "Philippines",
            "city" => "Manila",
            "zipcode" => "1008",
            "street" => "Sampaloc",
        ]);

        $this->assertTrue($creation);
        # assert the record if the data is added
    }

    /** @test */
    function should_delete_address()
    {
        // Create dummy record
        $this->repository->addShippingAddress([
            "client_id" => "1",
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $this->repository->addShippingAddress([
            "client_id" => "1",
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $this->repository->addShippingAddress([
            "client_id" => "1",
            "country"   => "Philippines",
            "city"      => "Manila",
            "zipcode"   => "1008",
            "street"    => "Sampaloc",
        ]);

        $deletion = $this->repository->removeAddress(1, 2);

        $this->assertTrue($deletion);
        # TODO :: modify assertion
    }

    /** @test */
    function should_update_address()
    {
        $this->repository->addShippingAddress([
            "client_id"  => "1",
            "country"    => "Philippines 1",
            "city"       => "Manila",
            "zipcode"    => "1008",
            "street"     => "Sampaloc",
            "is_default" => true,
        ]);

        $this->repository->addShippingAddress([
            "client_id"  => "1",
            "country"    => "Philippines 2",
            "city"       => "Manila",
            "zipcode"    => "1008",
            "street"     => "Sampaloc",
            "is_default" => true,
        ]);

        $this->repository->updateAddress(2, $payload = [
            'country' => 'Updated Country',
            'city' => 'Updated City',
            'zipcode' => 'Updated Zipcode',
            'street' => 'Updated Street',
        ]);

        $comparison = [
            'country' => $this->repository->getAddresses()[1]->country,
            'city' => $this->repository->getAddresses()[1]->city,
            'zipcode' => $this->repository->getAddresses()[1]->zipcode,
            'street' => $this->repository->getAddresses()[1]->street,
        ];

        $this->assertEqualsCanonicalizing($comparison, $payload);
    }
}
