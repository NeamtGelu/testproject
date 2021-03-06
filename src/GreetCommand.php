<?php

namespace Gelu;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class GreetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('car:option')
            ->setDescription('Cars operation :');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select what operation you want to do',
            array('If you want get a list of vehicles models makes by year type "0" ',
                   'If you want get vehicle information makes by VIN type "1" ',
                   'If you want get a list of vehicles models by car nickname and year make type "2" '),
            0
        );
        $question->setErrorMessage('Invalid choise. Please insert a valid number');

        $choise = $helper->ask($input, $output, $question);

        $var = $question->getChoices();

        $choiseKey = array_search($choise, $var);

        switch ($choiseKey) {
            case '0':
                $question = new Question('Please insert "year" to get a list of vehicles models makes in selected year.'."\n");

                $year = $helper->ask($input, $output, $question);

                if ($year > 1990 && $year <= date('Y')) {

                    $cSession = curl_init();

                    $url = "https://api.edmunds.com/api/vehicle/v2/makes?state=used&year={$year}&view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";
                    curl_setopt($cSession, CURLOPT_URL, $url);
                    curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($cSession);
                    $json = json_decode($result);

                    curl_close($cSession);

                    foreach ($json->makes as $makes) {
                        echo $makes->name . "\n";
                    };

                    $text = 'Here you have a list of ' . $json->makesCount . ' vehicles models makes in year "'.$year.'" ';

                } else {
                    $text = 'Please insert a valid "year" to get a list of vehicles. Year should be a four-digit number.';
                }

                $output->writeln($text);

                break;

            case '1':

                $question = new Question('Please enter the vehicle "VIN" to get all vehicle details from make, model, year, engine and other options'."\n");

                    $vin = $helper->ask($input, $output, $question);

                    if (strlen($vin) == 17) {

                        $cSession = curl_init();

                        $url = "https://api.edmunds.com/api/vehicle/v2/vins/{$vin}?fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";
                        curl_setopt($cSession, CURLOPT_URL, $url);
                        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                        $result = curl_exec($cSession);
                        $json = json_decode($result);

                        curl_close($cSession);

                        if (isset($json->errorType) && $json->errorType =='INCORRECT_PARAMS') {

                            $text = 'We don\'t have informations for this "VIN"';
                        }
                        else{

                            $text = "\n" . 'Vehicle details for VIN: ' . $vin . ':' .
                                (isset($json->make->name) ? "\n" . 'Make: ' . $json->make->name : '') .
                                (isset($json->model->name) ? "\n" . 'Model: ' . $json->model->name : '') .
                                (isset($json->engine->size) ? "\n" . 'Engine size: ' . $json->engine->size : '') .
                                (isset($json->engine->horsepower) ? "\n" . 'Horse power: ' . $json->engine->horsepower : '') .
                                (isset($json->years->year) ? "\n" . 'Year: ' . $json->years->year : '') .
                                (isset($json->numOfDoors) ? "\n" . 'Number of Doors: ' . $json->numOfDoors : '') .
                                (isset($json->categories->vehicleSize) ? "\n" . 'Style: ' . $json->categories->vehicleSize : '') .
                                (isset($json->categories->EPAClass) ? "\n" . 'Class: ' . $json->categories->EPAClass : '');
                        }

                    }
                    else {
                        $text = 'The VIN is incorrect. It must be 17 characters.';
                    }

                    $output->writeln($text);

                break;

            case '2':

                $question = new Question('Please insert "car nickname and year" to get a list of all Models vehicle by its Make/Year'.
                    "\n".'Exemple : audi / audi 2015'."\n");

                $data = $helper->ask($input, $output, $question);

                $data = explode(' ', $data);

                $nickname = $data[0];

                if(isset($data[1])) {
                    $year = $data[1];
                }
                else $year = '';

                if ($year !='') {

                    $urlType = "https://api.edmunds.com/api/vehicle/v2/{$nickname}/models?year={$year}&view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";

                    $textType = 'Here you have a list of car models for car nickname: "' . $nickname . '" make in year "' . $year . '" :';

                    $textError = 'No results for make vehicle "'.$nickname.'" make in year "' . $year . '" !';

                } else {

                    $urlType = "https://api.edmunds.com/api/vehicle/v2/{$nickname}/models?view=basic&fmt=json&api_key=y2zqcuv5n9cnm4d2g8yw6p9w";

                    $textType = 'Here you have a list of car models for car nickname: "' . $nickname . '" ';

                    $textError = 'No results for make vehicle "'.$nickname.'" !';

                }
                $cSession = curl_init();

                $url = $urlType;
                curl_setopt($cSession, CURLOPT_URL, $url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($cSession);
                $json = json_decode($result);

                curl_close($cSession);

                $listVehicles = '';

                if (!empty($json->models)) {

                    foreach ($json->models as $models) {

                        foreach ($models->years as $yearsList) {

                            foreach ($yearsList->styles as $style) {

                                $listVehicles .= "\n " . $nickname . " -> " . $models->name . " -> " . $yearsList->year . " -> " . $style->name;
                            }
                        }
                    };

                    $text = $listVehicles . "\n" . $textType;
                }
                else{
                    $text = "\n" . $textError;
                }

                $output->writeln($text);

                break;

        }
    }

}