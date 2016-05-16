<?php

require __DIR__.'/../vendor/autoload.php';

use Gelu\GreetCommand;
use Symfony\Component\Console\Application;

$command = new GreetCommand();

$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();

