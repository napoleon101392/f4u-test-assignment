<?php

namespace App\Console;

use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddAddress extends Command
{
    protected $client;

    const ACTION = '0';

    public function __construct()
    {
        $this->dataService = new DataService(__DIR__ . '/../../storage/database.json');

        $this->repository = new ClientRepository($this->dataService);

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition == self::ACTION) {
            $question = new Question('Enter country: ', 0);
            $country  = $this->helper->ask($input, $output, $question);

            $question = new Question('Enter city: ', 0);
            $city     = $this->helper->ask($input, $output, $question);

            $question = new Question('Enter Zipcode: ', 0);
            $zipcode  = $this->helper->ask($input, $output, $question);

            $question = new Question('Enter Street: ', 0);
            $street   = $this->helper->ask($input, $output, $question);

            $this->repository->addShippingAddress([
                "client_id" => $this->client,
                "country"   => $country,
                "city"      => $city,
                "zipcode"   => $zipcode,
                "street"    => $street,
            ]);

            $this->displayAddress($output);
        }
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

    private function displayAddress($output)
    {
        try { // Display Addresses
            $addresses = $this->repository->getAddressesByClient($this->client);
            foreach ($addresses as $key => $address) {
                $adds[] = $address->country;
                $adds[] = $address->city;
                $adds[] = $address->zipcode;
                $adds[] = $address->street;
                $adds[] = $address->is_default ? 'Yes' : 'No';
            }

            $table = new Table($output);
            $table
                ->setHeaders([
                    ["List of Address under choosen client"],
                    ['Country', 'City', 'Zipcode', 'Street', 'Default Address'],
                ])
                ->setRows(array_chunk($adds, 5));

            return $table->render();
        } catch (\Exception $e) {}
    }
}
