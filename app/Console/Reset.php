<?php

namespace App\Console;

use App\Console\Actions\BaseAction;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Reset extends BaseAction
{
    protected static $defaultName = 'reset';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $src = __DIR__ . "/../../storage/database.json.example";

        $dest = __DIR__ . "/../../storage/database.json";
        
        exec("cp '$src' '$dest'");

        return 0;
    }
}