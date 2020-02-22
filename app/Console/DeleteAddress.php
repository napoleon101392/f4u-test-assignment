<?php

namespace App\Console;

use Napoleon\Repositories\ClientRepository;
use Napoleon\Services\DataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class DeleteAddress extends Command
{
    protected $client;

    const ACTION = '2';

    public function __construct()
    {
        $this->dataService = new DataService(__DIR__ . '/../../storage/database.json');

        $this->repository = new ClientRepository($this->dataService);

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition == self::ACTION) {
            $addresses = $this->repository->getAddressesByClient($this->client);
            $helper    = $this->helper;

            foreach ($addresses as $value) {
                $default = $value->is_default ? 'Yes' : 'No';

                $address[$value->id] = $value->country . " | Default ? " . $default;
            }

            if ($this->condition == self::ACTION) {
                $question = new ChoiceQuestion('Choose address to Delete: ', $address, 0);
                $question->setErrorMessage('Address %s is not in the list');
                $question->setValidator(function ($addressId) use ($output) {
                    $this->repository->removeAddress($this->client, $addressId);

                    # Message successfully Delete
                    $this->displayAddress($output);
                });

                $helper->ask($input, $output, $question);
            }
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
