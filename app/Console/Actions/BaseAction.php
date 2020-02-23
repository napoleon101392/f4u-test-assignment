<?php

namespace App\Console\Actions;

use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;

class BaseAction extends Command
{
    protected $service;
    protected $repository;

    public function __construct()
    {
        $this->service = new DataService(__DIR__ . '/../../../storage/database.json');
        $this->repository = new ClientRepository($this->service);

        parent::__construct();
    }

    protected function displayAddress($output)
    {
        try { // Display Addresses
            $addresses = $this->repository->getAddressesByClient($this->client);

            $showableAddresses = [];

            foreach ($addresses as $key => $address) {
                $showableAddresses[] = $address->country;
                $showableAddresses[] = $address->city;
                $showableAddresses[] = $address->zipcode;
                $showableAddresses[] = $address->street;
                $showableAddresses[] = $address->is_default ? 'Yes' : 'No';
            }

            $table = new Table($output);
            $table
                ->setHeaders([
                    ["List of Address under choosen client"],
                    ['Country', 'City', 'Zipcode', 'Street', 'Default Address'],
                ])
                ->setRows(array_chunk($showableAddresses, 5));

            return $table->render();
        } catch (\Exception $e) {}
    }

    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    public function condition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    public function setHelper($helper)
    {
        $this->helper = $helper;

        return $this;
    }
}