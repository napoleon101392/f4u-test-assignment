<?php

namespace App\Console\Actions;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddAddress extends BaseAction
{
    protected $client;

    const ACTION = '0';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition != self::ACTION) {
            return;
        }

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
