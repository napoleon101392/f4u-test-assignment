<?php

namespace App\Console\Actions;

use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class BaseAction extends Command
{
    /** Directory of the json file to be manipulated */
    const JSON_DATABASE = __DIR__ . '/../../../storage/database.json';

    /** Handles the selected client */
    protected $client;

    /** Manipulator for data */
    protected $repository;

    public function __construct()
    {
        $this->repository = new ClientRepository(
            new DataService(self::JSON_DATABASE)
        );

        parent::__construct();
    }
    
    /**
     * Display the address as table representation
     *
     * @param ConsoleOutput $output
     * @return void
     */
    protected function displayAddress(ConsoleOutput $output)
    {
        try {
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
                ->setRows(
                    array_chunk($showableAddresses, 5)
                );

            return $table->render();
        } catch (\Exception $e) {}
    }

    /**
     * Mutate the selected client
     *
     * @param string $client
     * @return self
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Mutate the selected action for the address
     *
     * @param string $condition
     * @return self
     */
    public function condition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Mutate the helper for questions of symfony
     *
     * @param mixed $helper
     * @return self
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;

        return $this;
    }
}