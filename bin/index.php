<?php

require __DIR__.'/../vendor/autoload.php';

use Gelu\Vehicle;
use Symfony\Component\Console\Application;

$command = new Vehicle();

$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();

