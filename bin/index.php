<?php

require __DIR__.'/../vendor/autoload.php';

use Gelu\GreetCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new GreetCommand());
$application->run();



