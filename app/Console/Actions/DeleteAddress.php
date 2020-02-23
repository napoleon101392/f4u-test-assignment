<?php

namespace App\Console\Actions;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class DeleteAddress extends BaseAction
{
    const ACTION = '2';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition != self::ACTION) {
            return;
        }

        try {
            $addresses = $this->repository->getAddressesByClient($this->client);
            $helper    = $this->helper;
    
            foreach ($addresses as $value) {
                $default = $value->is_default ? 'Yes' : 'No';
    
                $address[$value->id] = $value->country . " | Default ? " . $default;
            }
    
            $question = new ChoiceQuestion('Choose address to Delete: ', $address, 0);
            $question->setErrorMessage('Address %s is not in the list');
            $question->setValidator(function ($addressId) use ($output) {
                $this->repository->removeAddress($this->client, $addressId);
    
                $this->displayAddress($output);
            });
    
            $helper->ask($input, $output, $question);
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $output->writeln("<error>$message</error>");
        }
    }
}
