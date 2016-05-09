<?php

namespace Gelu;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GreetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cars')
            ->setDescription('Cars operations')
            ->addArgument(
                'year',
                InputArgument::OPTIONAL,
                'What do you want to greet?'
            )
            ->addArgument(
                'vin',
                InputArgument::OPTIONAL,
                'Get Vehicle Details by VIN?'
            )
            ->addArgument(
                'make',
                InputArgument::OPTIONAL,
                'Get All Car Models by a Car Make and Year?'
            )

            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        $vin = $input->getArgument('vin');
        $make = $input->getArgument('make');

        if($year) {

            if($year != '-') {


                if ($year > 1990 && $year <= date('Y')) {

//            $request =  'https://api.edmunds.com/api/vehicle/v2/makes?state=used&year='.$year.'&view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w';
//            $response  = file_get_contents($request);
//            $json  = json_decode($response);

                    //print_r($json);

//            foreach ($json->makes as $makes) {
//                echo $makes->name . "\n";
//            }

                    $cSession = curl_init();

                    $url = "https://api.edmunds.com/api/vehicle/v2/makes?state=used&year={$year}&view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";
                    curl_setopt($cSession, CURLOPT_URL, $url);
                    curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($cSession);
                    $json = json_decode($result);

                    curl_close($cSession);

                    //print_r($json);

                    foreach ($json->makes as $makes) {
                        echo $makes->name . "\n";
                    };

                    $text = 'Here you have a list of ' . $json->makesCount . ' vehicles models makes in year " '.$year.' " ';

                } else {
                    $text = 'Please insert a valid "year" to get a list of vehicles. Year should be a four-digit number.';
                }


                if ($input->getOption('yell')) {
                    $text = strtoupper($text);
                }

                $output->writeln($text);
            }
        }


        if($vin) {
            if ($vin != '-') {
                if (strlen($vin) == 17) {

                    $cSession = curl_init();

                    $url = "https://api.edmunds.com/api/vehicle/v2/vins/{$vin}?fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";
                    curl_setopt($cSession, CURLOPT_URL, $url);
                    curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($cSession);
                    $json = json_decode($result);

                    curl_close($cSession);

//                print_r($json);

                    $text = "\n" . 'Vehicle details for VIN: ' . $vin . ':' .
                    (isset($json->make->name) ? "\n" . 'Make: ' . $json->make->name : '') .
                    (isset($json->model->name) ? "\n" . 'Model: ' . $json->model->name : '') .
                    (isset($json->engine->size) ? "\n" . 'Engine size: ' . $json->engine->size : '') .
                    (isset($json->engine->horsepower) ? "\n" . 'Horse powe: ' . $json->engine->horsepower : '') .
                    (isset($json->years->year) ? "\n" . 'Year: ' . $json->years->year : '') .
                    (isset($json->numOfDoors) ? "\n" . 'Number of Doors: ' . $json->numOfDoors : '') .
                    (isset($json->categories->vehicleSize) ? "\n" . 'Style: ' . $json->categories->vehicleSize : '') .
                    (isset($json->categories->EPAClass) ? "\n" . 'Class: ' . $json->categories->EPAClass : '');

                } else {
                    $text = 'The VIN is incorrect. It must be 17 characters.';
                }


                if ($input->getOption('yell')) {
                    $text = strtoupper($text);
                }

                $output->writeln($text);

            }
        }

        if($make) {
            if ($make != '-') {
                if ($year != '-') {

                    $url_type = "https://api.edmunds.com/api/vehicle/v2/{$make}/models?year={$year}&view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";

                    $text_type = 'Here you have a list of car models for car nickname: "' . $make . '" make in year "' . $year . '" :';

                    $text_error = 'No results for make vehicle "'.$make.'" make in year "' . $year . '" !';

                } else {

                    $url_type = "https://api.edmunds.com/api/vehicle/v2/{$make}/models?view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";

                    $text_type = 'Here you have a list of car models for car nickname: "' . $make . '" ';

                    $text_error = 'No results for make vehicle "'.$make.'" !';

                }
                $cSession = curl_init();

                $url = $url_type;
                curl_setopt($cSession, CURLOPT_URL, $url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($cSession);
                $json = json_decode($result);

                curl_close($cSession);

                //print_r($json->models);

                $list_vehicles = '';

                if (!empty($json->models)) {

                    foreach ($json->models as $models) {

                        foreach ($models->years as $years_list) {

                            foreach ($years_list->styles as $style) {

                                $list_vehicles .= "\n " . $make . " -> " . $models->name . " -> " . $years_list->year . " -> " . $style->name;
                            }
                        }
                    };

                    $text = $list_vehicles . "\n" . $text_type;
                }
                else{
                    $text = "\n" . $text_error;
                }

                $output->writeln($text);

            }
        }
    }

}