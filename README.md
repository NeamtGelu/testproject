Hello !

Welcome to Console Project Edmunds API

Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

Get this project from GitHub using:

git clone git@github.com:NeamtGelu/testproject.git

After that, you can run project using line command.
Open your Terminal and change directory to root project.

cd /root/testproject

To run application type

php bin/index.php

You will show a list of options like that:

  [0] If you want get a list of vehicles models makes by year type "0"
  [1] If you want get vehicle information makes by VIN type "1"
  [2] If you want get a list of vehicles models by car nickname and year make type "2"

Select what you want to do from list of options: 0, 1, or 2 and press ENTER.

If you choise "0", next step is to insert YEAR in interval 1990 adn current year.

After you press ENTER, if year is correct, will display a list of cars name make in year entered.
Else, you will show a error message.

For choise "1", you need to have a valid VIN vehicle that must be 17 characters and to paste it.
Here you have a test VIN for test : 1N4AL3AP4DC295509 or 2G1FC3D33C9165616

You will get all vehicle details from make, model, year, engine and other options

And for last option "2" you can insert only car "nickname" to show a list of car models, or if you want to get car models for a specific "year" write that too.
Exemple 1: audi
Exemple 2 : audi 2010

That's all !

Thanke you for using this tool :)