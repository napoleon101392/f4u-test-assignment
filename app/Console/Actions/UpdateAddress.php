<?php

namespace App\Console\Actions;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class UpdateAddress extends BaseAction
{
    protected $client;

    const ACTION = '1';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition != self::ACTION) {
            return;
        }

        $addresses = $this->repository->getAddressesByClient($this->client);
        $helper = $this->helper;

        foreach ($addresses as $value) {
            $default = $value->is_default ? 'Yes' : 'No';

            $address[$value->id] = $value->country . " | Default: " . $default;
        }

        $question = new ChoiceQuestion('Choose address to update: ', $address, 0);
        $question->setErrorMessage('Address %s is not in the list');
        $question->setValidator(function ($answer) use ($input, $output, $helper) {
            $question = new Question('Enter country: ', 0);
            $country  = $helper->ask($input, $output, $question);

            $question = new Question('Enter city: ', 0);
            $city     = $helper->ask($input, $output, $question);

            $question = new Question('Enter Zipcode: ', 0);
            $zipcode  = $helper->ask($input, $output, $question);

            $question = new Question('Enter Street: ', 0);
            $street   = $helper->ask($input, $output, $question);

            $this->repository->updateAddress($answer, [
                'country' => $country,
                'city'    => $city,
                'zipcode' => $zipcode,
                'street'  => $street,
            ]);

            # Message successfully update
            $this->displayAddress($output);
        });

        $helper->ask($input, $output, $question);
    }
}
