<?php

require __DIR__.'/../vendor/autoload.php';

use Gelu\GreetCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

//$application = new Application();
//$application->add(new GreetCommand());
//$application->run();



$command = new GreetCommand();

$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();



//$application = new Application();
//$application->add(new GreetCommand());
//
//$command = $application->find('car');
//
//$commandTester = new CommandTester($command);
//$commandTester->execute(array('option' => $command->getName()));
//$commandTester->execute(array(
//    'option'      => $command->getName(),
//    'name'         => 'Fabien'
//));
//
//$this->assertRegExp('/.../', $commandTester->getDisplay());



