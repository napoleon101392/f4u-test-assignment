<?php

namespace App\Console;

use Napoleon\Services\DataService;
use Napoleon\Repositories\ClientRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Start extends Command
{
    protected static $defaultName = 'start';

    protected $choosenClient;

    public function __construct()
    {
        $this->dataService = new DataService(__DIR__ . '/../../storage/database.json');

        $this->repository = new ClientRepository($this->dataService);

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try { // Choose clients
            foreach ($this->repository->getClients() as $client) {
                $firstname = $client->firstname;
                $lastname  = $client->lastname;

                $clients[$client->id] = "$firstname $lastname";
            }

            $helper   = $this->getHelper('question');
            $question = new ChoiceQuestion('Please select Client in the list', $clients, 0);
            $question->setErrorMessage('Client %s is not in the list');
            $question->setValidator(function ($answer) {
                $this->choosenClient = $answer;
            });

            $helper->ask($input, $output, $question);

            $this->displayAddress($output);
        } catch (\Exception $e) {}

        try { // Choosing Action for address
            $helper   = $this->getHelper('question');
            $question = new ChoiceQuestion('Action to the addresses above', ['Add', 'Update', 'Delete'], 0);
            $question->setErrorMessage('Action %s is not in the list');
            $question->setValidator(function ($answer) use ($input, $output) {
                $client = $this->choosenClient;

                foreach ([
                    AddAddress::class,
                    UpdateAddress::class,
                    DeleteAddress::class
                ] as $class) {
                    (new $class)
                        ->setClient($client)
                        ->condition($answer)
                        ->setHelper($this->getHelper('question'))
                        ->execute($input, $output);
                }
            });

            $helper->ask($input, $output, $question);
        } catch (\Exception $e) {}

        return 0;
    }

    private function displayAddress($output)
    {
        try { // Display Addresses
            $addresses = $this->repository->getAddressesByClient($this->choosenClient);
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
