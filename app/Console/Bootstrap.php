<?php

namespace App\Console;

use Exception;
use App\Console\Actions\AddAddress;
use App\Console\Actions\BaseAction;
use App\Console\Actions\ExitAction;
use App\Console\Actions\DeleteAddress;
use App\Console\Actions\UpdateAddress;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Bootstrap extends BaseAction
{
    protected static $defaultName = 'start';

    protected $client;

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
                $this->client = $answer;
            });

            $helper->ask($input, $output, $question);

            $this->displayAddress($output);
        } catch (Exception $e) { return; }

        try { // Choosing Action for address
            $helper   = $this->getHelper('question');
            $question = new ChoiceQuestion('Action to the addresses above', [
                'Add', 'Update', 'Delete', 'Exit'
            ], 3);
            $question->setErrorMessage('Action %s is not in the list');
            $question->setValidator(function ($answer) use ($input, $output) {
                $client = $this->client;

                foreach ([
                    AddAddress::class,
                    UpdateAddress::class,
                    DeleteAddress::class,
                    ExitAction::class
                ] as $class) {
                    (new $class)
                        ->setClient($client)
                        ->condition($answer)
                        ->setHelper($this->getHelper('question'))
                        ->execute($input, $output);
                }
            });

            $helper->ask($input, $output, $question);
        } catch (Exception $e) { return; }

        return 0;
    }
}
