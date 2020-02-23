<?php

namespace App\Console\Actions;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExitAction extends BaseAction
{
    const ACTION = '3';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->condition == self::ACTION) {
            return 0;
        }
    }
}
