<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \App\Console\Start);
$application->add(new \App\Console\Reset);

$application->run();
